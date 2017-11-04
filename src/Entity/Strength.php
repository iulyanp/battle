<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Strength
 * @package Iulyanp\Battle\Entity
 */
class Strength
{
    private $strength;

    /**
     * Strength constructor.
     *
     * @param $minStrength
     * @param $maxStrength
     *
     * @throws \Exception
     */
    public function __construct($minStrength, $maxStrength)
    {
        $this->validate($minStrength, $maxStrength);

        $this->strength = mt_rand($minStrength, $maxStrength);
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->strength;
    }

    /**
     * @param $minStrength
     * @param $maxStrength
     *
     * @throws \Exception
     */
    protected function validate($minStrength, $maxStrength)
    {
        if (!is_numeric($minStrength)
            || !is_numeric($maxStrength)
            || (0 > $minStrength && 100 < $minStrength)
            || (0 > $maxStrength && 100 < $maxStrength)
        ) {
            throw new \Exception('Speed must be a number between 0 and 100, duh!');
        }
    }
}
