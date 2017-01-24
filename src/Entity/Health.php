<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Health
 * @package Iulyanp\Battle\Entity
 */
class Health
{
    private $health;

    /**
     * Health constructor.
     *
     * @param $minHealth
     * @param $maxHealth
     *
     * @throws \Exception
     */
    public function __construct($minHealth, $maxHealth)
    {
        $this->validate($minHealth, $maxHealth);
        $this->health = mt_rand($minHealth, $maxHealth);
    }

    /**
     * @param $health
     *
     * @return $this
     */
    public function set($health)
    {
        $this->health = $health;

        return $this;
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->health;
    }

    /**
     * @param $minHealth
     * @param $maxHealth
     *
     * @throws \Exception
     */
    protected function validate($minHealth, $maxHealth)
    {
        if (!is_numeric($minHealth)
            || !is_numeric($maxHealth)
            || (0 > $minHealth && 100 < $minHealth)
            || (0 > $maxHealth && 100 < $maxHealth)
        ) {
            throw new \Exception('Health must be a number between 0 and 100, duh!');
        }
    }
}
