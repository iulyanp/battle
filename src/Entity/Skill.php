<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Skill
 * @package Iulyanp\Battle\Entity
 */
class Skill
{
    const SKILL_TYPES = [self::ATTACK, self::DEFENCE];
    const ATTACK = 'onAttack';
    const DEFENCE = 'onDefence';

    private $name;
    private $type;
    private $probability;
    private $formula;
    private $status;
    private $used = false;

    /**
     * Skill constructor.
     *
     * @param $name
     * @param $type
     * @param $probability
     * @param $formula
     */
    public function __construct($name, $type, $probability, $formula)
    {
        $this->validate($type, $probability, $formula);

        $this->name = $name;
        $this->type = $type;
        $this->probability = $probability;
        $this->formula = str_replace('damage', '%s', $formula);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * @return mixed
     */
    public function getFormula()
    {
        return $this->formula;
    }

    public function wasUsed()
    {
        return $this->used;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        $this->status = mt_rand(1, 100) <= $this->probability;

        return $this->status;
    }

    /**
     * @param $defaultDamage
     *
     * @return float|int
     */
    public function getDamageWithActiveSkills($defaultDamage)
    {
        $damage = 0;

        $this->used = false;
        if ($this->isActive()) {
            $this->used = true;

            $formula = sprintf($this->getFormula(), $defaultDamage);

            list($a, $operator, $b) = explode(' ', $formula);

            switch ($operator) {
                case '*':
                    $damage = $a * $b;
                    break;
                case '/':
                    $damage = $a / $b;
                    break;
            }
        }

        return $damage;
    }

    /**
     * @param $type
     * @param $probability
     * @param $formula
     *
     * @throws \Exception
     */
    protected function validate($type, $probability, $formula)
    {
        if (!in_array($type, self::SKILL_TYPES)) {
            throw new \Exception(sprintf('Skill can be only of type %s or %s', self::ATTACK, self::DEFENCE));
        }

        if (!is_numeric($probability) || 0 > $probability || 100 < $probability) {
            throw new \Exception('Skill probability can be only numeric from 0 to 100.');
        }

        if (!preg_match('/^([0-9]|damage)+\s[\*\/]{1}\s(damage|[0-9])+$/m', $formula)) {
            throw new \Exception(
                "Skill formula can be only in the following forms: 
                [number] [operator] damage 
                OR 
                damage [operator] [number] 
                eg. 2 * damage"
            );
        }
    }
}
