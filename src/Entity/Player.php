<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Player
 * @package Iulyanp\Battle\Entity
 */
abstract class Player implements PlayerInterface
{
    protected $name;
    protected $health;
    protected $strength;
    protected $defence;
    protected $speed;
    protected $luck;
    protected $lucky;
    protected $damage = 0;
    protected $readyToAttack = false;
    protected $skills = [];
    protected $attackUsedSkills = [];
    protected $defendUsedSkills = [];

    /**
     * Player constructor.
     *
     * @param string   $name
     * @param Health   $health
     * @param Strength $strength
     * @param Defence  $defence
     * @param Speed    $speed
     * @param Luck     $luck
     *
     * @throws \Exception
     */
    function __construct($name, Health $health, Strength $strength, Defence $defence, Speed $speed, Luck $luck)
    {
        if (!is_string($name)) {
            throw new \Exception('Name should be a string.');
        }
        $this->name = $name;
        $this->health = $health;
        $this->strength = $strength;
        $this->defence = $defence;
        $this->speed = $speed;
        $this->luck = $luck;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @return array
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @return array
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @return array
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return array
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * @return bool
     */
    public function isLucky()
    {
        return $this->lucky = mt_rand(1, 100) <= $this->getLuck()->value();
    }

    /**
     * @return mixed
     */
    public function wasLucky()
    {
        return $this->lucky;
    }

    /**
     * @return $this
     */
    public function calculateHeathLeft()
    {
        $health = $this->getHealth()->value() - $this->damage;

        if ($health < 0) {
            $this->getHealth()->set(0);
        } else {
            $this->getHealth()->set($health);
        }

        return $this;
    }

    /**
     * @param int $damage
     *
     * @return Player
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * @return mixed
     */
    public function getReadyToAttack()
    {
        return $this->readyToAttack;
    }

    /**
     * @param mixed $readyToAttack
     *
     * @return Player
     */
    public function prepareToAttack($readyToAttack = true)
    {
        $this->readyToAttack = $readyToAttack;

        return $this;
    }

    /**
     * @return bool
     */
    public function isReadyToAttack()
    {
        return ($this->getReadyToAttack()) ? true : false;
    }

    /**
     * @param $attackStrength
     *
     * @return int
     */
    public function calculateDefaultDamage($attackStrength)
    {
        return intval($attackStrength - $this->getDefence()->value());
    }

    /**
     * @return mixed
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param Skill $skill
     *
     * @return $this
     */
    public function addSkill(Skill $skill)
    {
        $this->skills[$skill->getType()][$skill->getName()] = $skill;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttackSkills()
    {
        return (isset($this->skills[Skill::ATTACK])) ? $this->skills[Skill::ATTACK] : [];
    }

    /**
     * @return mixed
     */
    public function getDefenceSkills()
    {
        return (isset($this->skills[Skill::DEFENCE])) ? $this->skills[Skill::DEFENCE] : [];
    }

    /**
     * @param PlayerInterface $player
     *
     * @return $this
     */
    public function attack(PlayerInterface $player)
    {
        $player->prepareToAttack();
        $this->prepareToAttack(false);

        if ($player->isLucky()) {
            $player->setDamage(0);

            return $this;
        }

        $this->strike($player);

        $player->calculateHeathLeft()->getHealth()->value();

        return $this;
    }

    public function usedAttackSkills()
    {
        return (!empty($this->attackUsedSkills)) ? true : false;
    }

    public function usedDefendSkills()
    {
        return (!empty($this->defendUsedSkills)) ? true : false;
    }

    public function getAttackUsedSkills()
    {
        return $this->attackUsedSkills;
    }

    public function getDefendUsedSkills()
    {
        return $this->defendUsedSkills;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s: Speed:%s Strength:%s Health:%s, Damage:%s, Luck:%s',
            $this->getName(),
            $this->getSpeed()->value(),
            $this->getStrength()->value(),
            $this->getHealth()->value(),
            $this->getDefence()->value(),
            $this->getLuck()->value()
        );
    }

    /**
     * @param PlayerInterface $player
     *
     * @return number
     */
    private function strike(PlayerInterface $player)
    {
        $defaultDamage = $player->calculateDefaultDamage($this->getStrength()->value());

        $damage = $this->strikeWithSkills($player, $defaultDamage);

        return $player->defend($damage);
    }

    protected abstract function strikeWithSkills($player, $damage);

    protected abstract function defend($damage);
}
