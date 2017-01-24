<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Defence
 * @package Iulyanp\Battle\Entity
 */
class Defence
{
    private $defence;

    /**
     * Defence constructor.
     *
     * @param $minDefence
     * @param $maxDefence
     *
     * @throws \Exception
     */
    public function __construct($minDefence, $maxDefence)
    {
        $this->validate($minDefence, $maxDefence);
        $this->defence = mt_rand($minDefence, $maxDefence);
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->defence;
    }

    /**
     * @param $minDefence
     * @param $maxDefence
     *
     * @throws \Exception
     */
    protected function validate($minDefence, $maxDefence)
    {
        if (!is_numeric($minDefence)
            || !is_numeric($maxDefence)
            || (0 > $minDefence && 100 < $minDefence)
            || (0 > $maxDefence && 100 < $maxDefence)
        ) {
            throw new \Exception('Speed must be a number between 0 and 100, duh!');
        }
    }
}
