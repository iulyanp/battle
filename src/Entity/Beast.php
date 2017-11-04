<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Beast
 */
class Beast extends Player
{
    /**
     * @param $damage
     *
     * @return mixed
     */
    protected function defend($damage)
    {
        return $damage;
    }

    /**
     * @param $player
     * @param $damage
     *
     * @return mixed
     */
    protected function strikeWithSkills(PlayerInterface $player, $damage)
    {
        return $damage;
    }
}
