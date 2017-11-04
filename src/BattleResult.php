<?php

namespace Iulyanp\Battle;

use Iulyanp\Battle\Entity\Player;
use Iulyanp\Battle\Entity\PlayerInterface;
use Iulyanp\Battle\Entity\Skill;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BattleResult
 * @package Iulyanp\Battle
 */
class BattleResult implements BattleResultInterface
{
    use OutputTrait;
    /**
     * @var OutputInterface
     */
    private $output;
    /**
     * @var Player
     */
    private $attacker;
    /**
     * @var Player
     */
    private $defender;

    /**
     * BattleResult constructor.
     *
     * @param PlayerInterface $player1
     * @param PlayerInterface $player2
     * @param OutputInterface $output
     */
    public function __construct(PlayerInterface $player1, PlayerInterface $player2, OutputInterface $output)
    {
        $this->output = $output;

        if ($player1->isReadyToAttack()) {
            $this->attacker = $player1;
            $this->defender = $player2;
        }

        if ($player2->isReadyToAttack()) {
            $this->attacker = $player2;
            $this->defender = $player1;
        }
    }

    /**
     * @param $round
     */
    public function getRoundResult($round)
    {
        $this->write('------------------------------');
        $this->write(
            sprintf(
                'Round %s - %s will attack - %s will defend',
                $round,
                $this->attacker->getName(),
                $this->defender->getName()
            )
        );
        $this->write('------------------------------');

        if ($this->attacker->usedAttackSkills()) {
            $usedSkills = array_map(function ($skill) {
                /** @var $skill Skill */
                return $skill->getName();
            }, $this->attacker->getAttackUsedSkills());

            $this->info(
                sprintf(
                    '%s used skill to attack %s',
                    $this->attacker->getName(),
                    implode(', ', $usedSkills)
                )
            );
        }

        if ($this->defender->wasLucky()) {
            $this->comment(
                sprintf(
                    '%s got lucky to %s attack.',
                    $this->defender->getName(),
                    $this->attacker->getName()
                )
            );
        }

        if ($this->defender->usedDefendSkills() && !$this->defender->wasLucky()) {

            $usedSkills = array_map(function ($skill) {
                /** @var $skill Skill */
                return $skill->getName();
            }, $this->defender->getDefendUsedSkills());

            $this->info(
                sprintf(
                    '%s used skill to defend %s',
                    $this->defender->getName(),
                    implode(', ', $usedSkills)
                )
            );
        }

        $this->write(
            sprintf('%s got damaged by %s.', $this->defender->getName(), $this->defender->getDamage())
        );
        $this->write(
            sprintf("%s's health left %s.", $this->defender->getName(), $this->defender->getHealth()->value())
        );
    }

    /**
     * Returns the result of the battle
     */
    public function getResult()
    {
        $this->write(PHP_EOL . '==============================');

        if ($this->defender->getHealth()->value() == 0) {
            $this->write(sprintf('<error>%s was killed!</error>', $this->defender->getName()));

            return;
        }

        if ($this->attacker->getHealth()->value() == $this->defender->getHealth()->value()) {
            $this->error(sprintf('It is a draw!'));

            return;
        }

        if ($this->attacker->getHealth()->value() < $this->defender->getHealth()->value()) {
            $this->error(sprintf('%s was defeated!', $this->attacker->getName()));

            return;
        } else {
            $this->error(sprintf('%s was defeated!', $this->defender->getName()));

            return;
        }
    }
}
