<?php

namespace Iulyanp\Battle\Entity;

/**
 * Interface PlayerInterface
 * @package Iulyanp\Battle
 */
interface PlayerInterface
{
    public function getName();
    public function getHealth();
    public function getSpeed();
    public function getStrength();
    public function getDefence();
    public function getLuck();
    public function calculateDefaultDamage($attackStrength);
}
