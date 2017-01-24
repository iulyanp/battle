<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Luck
 * @package Iulyanp\Battle\Entity
 */
class Luck
{
    private $luck;

    /**
     * Luck constructor.
     *
     * @param $minLuck
     * @param $maxLuck
     *
     * @throws \Exception
     */
    public function __construct($minLuck, $maxLuck)
    {
        $this->validate($minLuck, $maxLuck);
        $this->luck = mt_rand($minLuck, $maxLuck);
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->luck;
    }

    /**
     * @param $minLuck
     * @param $maxLuck
     *
     * @throws \Exception
     */
    protected function validate($minLuck, $maxLuck)
    {
        if (!is_numeric($minLuck)
            || !is_numeric($maxLuck)
            || (0 > $minLuck && 100 < $minLuck)
            || (0 > $maxLuck && 100 < $maxLuck)
        ) {
            throw new \Exception('Luck must be a number between 0 and 100, duh!');
        }
    }
}
