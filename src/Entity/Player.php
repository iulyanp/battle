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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Health
     */
    public function getHealth()
    {
        return $this->health;
    }

    /**
     * @return Strength
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @return Defence
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * @return Speed
     */
    public function getSpeed()
    {
        return $this->speed;
    }

    /**
     * @return Luck
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
     * @return bool
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
     * @return $this
     */
    public function setDamage($damage)
    {
        $this->damage = $damage;

        return $this;
    }

    /**
     * @return int
     */
    public function getDamage()
    {
        return $this->damage;
    }

    /**
     * @return bool
     */
    public function getReadyToAttack()
    {
        return $this->readyToAttack;
    }

    /**
     * @param bool $readyToAttack
     *
     * @return $this
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
     * @return array
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
     * @return array
     */
    public function getAttackSkills()
    {
        return (isset($this->skills[Skill::ATTACK])) ? $this->skills[Skill::ATTACK] : [];
    }

    /**
     * @return array
     */
    public function getDefenceSkills()
    {
        return (isset($this->skills[Skill::DEFENCE])) ? $this->skills[Skill::DEFENCE] : [];
    }

    /**
     * @param Player $player
     *
     * @return $this
     */
    public function attack(Player $player)
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

    protected abstract function strikeWithSkills(PlayerInterface $player, $damage);

    protected abstract function defend($damage);

    /**
     * @param Player $player
     *
     * @return number
     */
    private function strike(Player $player)
    {
        $defaultDamage = $player->calculateDefaultDamage($this->getStrength()->value());

        $damage = $this->strikeWithSkills($player, $defaultDamage);

        return $player->defend($damage);
    }
}
