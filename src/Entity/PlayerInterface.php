<?php

namespace Iulyanp\Battle\Entity;

/**
 * Interface PlayerInterface
 * @package Iulyanp\Battle
 */
interface PlayerInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return Health
     */
    public function getHealth();

    /**
     * @return Speed
     */
    public function getSpeed();

    /**
     * @return Strength
     */
    public function getStrength();

    /**
     * @return Defence
     */
    public function getDefence();

    /**
     * @return Luck
     */
    public function getLuck();

    /**
     * @param $damage
     *
     * @return $this
     */
    public function setDamage($damage);

    /**
     * @return $this
     */
    public function prepareToAttack();

    /**
     * @return bool
     */
    public function isLucky();

    /**
     * @return $this
     */
    public function calculateHeathLeft();

    /**
     * @return bool
     */
    public function isReadyToAttack();

    /**
     * @param Player $player
     *
     * @return $this
     */
    public function attack(Player $player);

    /**
     * @param $attackStrength
     *
     * @return int
     */
    public function calculateDefaultDamage($attackStrength);
}
