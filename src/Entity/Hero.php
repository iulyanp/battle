<?php

namespace Iulyanp\Battle\Entity;

/**
 * Class Hero
 * @package Iulyanp\Battle\Entity
 */
class Hero extends Player
{

    /**
     * @param $player
     * @param $defaultDamage
     *
     * @return array
     */
    protected function strikeWithSkills(PlayerInterface $player, $defaultDamage)
    {
        $damage = [];
        $this->attackUsedSkills = [];
        foreach ($this->getAttackSkills() as $skill) {
            /** @var $skill Skill */
            $damage[] = $skill->getDamageWithActiveSkills($defaultDamage);
            if ($skill->wasUsed()) {
                $this->attackUsedSkills[] = $skill;
            }
        }

        $totalDamage = (empty(array_filter($damage))) ? $defaultDamage : array_sum($damage);

        return $player->setDamage($totalDamage);
    }

    /**
     * @param $defaultDamage
     *
     * @return Player
     */
    protected function defend($defaultDamage)
    {
        $damage = [];
        $this->defendUsedSkills = [];
        foreach ($this->getDefenceSkills() as $skill) {
            /** @var $skill Skill */
            $damage[] = $skill->getDamageWithActiveSkills($defaultDamage);
            if ($skill->wasUsed()) {
                $this->defendUsedSkills[] = $skill;
            }
        }

        $totalDamage = (empty(array_filter($damage))) ? $defaultDamage : array_sum($damage);

        return $this->setDamage($totalDamage);
    }
}
