<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Speed
 * @package Iulyanp\Battle\Entity
 */
class Speed
{
    private $speed;

    /**
     * Speed constructor.
     *
     * @param $minSpeed
     * @param $maxSpeed
     *
     * @throws \Exception
     */
    public function __construct($minSpeed, $maxSpeed)
    {
        $this->validate($minSpeed, $maxSpeed);

        $this->speed = mt_rand($minSpeed, $maxSpeed);
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->speed;
    }

    /**
     * @param $minSpeed
     * @param $maxSpeed
     *
     * @throws \Exception
     */
    protected function validate($minSpeed, $maxSpeed)
    {
        if (!is_numeric($minSpeed)
            || !is_numeric($maxSpeed)
            || (0 > $minSpeed && 100 < $minSpeed)
            || (0 > $maxSpeed && 100 < $maxSpeed)
        ) {
            throw new \Exception('Speed must be a number between 0 and 100, duh!');
        }
    }
}
