<?php

namespace Iulyanp\Battle\Tests;

use Iulyanp\Battle\BattleResult;
use Iulyanp\Battle\Entity\Beast;
use Iulyanp\Battle\Entity\Defence;
use Iulyanp\Battle\Entity\Health;
use Iulyanp\Battle\Entity\Hero;
use Iulyanp\Battle\Entity\Luck;
use Iulyanp\Battle\Entity\Skill;
use Iulyanp\Battle\Entity\Speed;
use Iulyanp\Battle\Entity\Strength;

/**
 * Class BattleResultTest
 * @package Iulyanp\Battle\Tests
 */
class BattleResultTest extends \PHPUnit_Framework_TestCase
{
    private $output;

    public function setUp()
    {
        $this->output = $this->getMockBuilder('Symfony\Component\Console\Output\OutputInterface')->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     */
    public function get_round_result_on_hero_attack()
    {
        $hero = $this->createUnluckyHero();
        $hero->isLucky();
        $hero->prepareToAttack();
        $beast = $this->createUnluckyBeast();
        $beast->isLucky();

        $this->output
            ->expects($this->exactly(5))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_round_result_on_beast_attack()
    {
        $beast = $this->createUnluckyBeast();
        $beast->isLucky();
        $beast->prepareToAttack();
        $beast->wasLucky();

        $hero = $this->createUnluckyHero();
        $hero->isLucky();

        $this->output
            ->expects($this->exactly(5))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_round_result_on_hero_attack_but_beast_is_lucky()
    {
        $hero = $this->createUnluckyHero();
        $hero->isLucky();
        $hero->prepareToAttack();
        $beast = $this->createLuckyBeast();
        $beast->isLucky();
        $beast->wasLucky();

        $this->output
            ->expects($this->exactly(6))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_round_result_on_beast_attack_but_hero_is_lucky()
    {
        $hero = $this->createLuckyHero();
        $hero->isLucky();
        $beast = $this->createUnluckyBeast();
        $beast->isLucky();
        $beast->prepareToAttack();
        $beast->wasLucky();

        $this->output
            ->expects($this->exactly(6))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_result_when_rounds_ended()
    {
        $hero = $this->createUnluckyHero();
        $beast = $this->createUnluckyBeast();
        $beast->prepareToAttack();

        $this->output
            ->expects($this->exactly(2))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getResult();
    }

    /**
     * @test
     */
    public function get_result_when_defender_is_killed()
    {
        $hero = $this->createUnluckyHero();
        $hero->prepareToAttack();
        $beast = $this->createUnluckyBeast();
        $beast->getHealth()->set(0);

        $this->output
            ->expects($this->exactly(2))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getResult();
    }

    /**
     * @test
     */
    public function get_result_when_both_players_have_same_health_and_rounds_ended()
    {
        $hero = $this->createUnluckyHero();
        $hero->prepareToAttack();
        $hero->getHealth()->set(20);
        $beast = $this->createUnluckyBeast();
        $beast->getHealth()->set(20);

        $this->output
            ->expects($this->exactly(2))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getResult();
    }


    /**
     * @test
     */
    public function get_result_when_attacker_has_more_health_left_and_rounds_ended()
    {
        $hero = $this->createUnluckyHero();
        $hero->prepareToAttack();
        $hero->getHealth()->set(50);
        $beast = $this->createUnluckyBeast();
        $beast->getHealth()->set(20);

        $this->output
            ->expects($this->exactly(2))
            ->method('writeln');

        $battleResult = new BattleResult($hero, $beast, $this->output);
        $battleResult->getResult();
    }

    /**
     * @test
     */
    public function get_round_result_on_hero_attack_with_skill()
    {
        $hero = $this->createUnluckyHero();
        $hero->addSkill(new Skill('test', Skill::ATTACK, 100, 'damage * 2'));
        $beast = $this->createUnluckyBeast();
        $hero->prepareToAttack();
        $battleResult = new BattleResult($hero, $beast, $this->output);

        $hero->attack($beast);

        $this->output
            ->expects($this->exactly(6))
            ->method('writeln');

        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_round_result_on_beast_attack_and_hero_defend_with_skill()
    {
        $hero = $this->createUnluckyHero();
        $hero->addSkill(new Skill('test', Skill::DEFENCE, 100, 'damage / 2'));
        $beast = $this->createUnluckyBeast();
        $beast->prepareToAttack();
        $battleResult = new BattleResult($hero, $beast, $this->output);
        $beast->attack($hero);

        $this->output
            ->expects($this->exactly(6))
            ->method('writeln');

        $battleResult->getRoundResult(1);
    }

    /**
     * @test
     */
    public function get_round_result_on_beast_attack_and_hero_is_lucky_and_defend_with_skill()
    {
        $hero = $this->createLuckyHero();
        $hero->addSkill(new Skill('test', Skill::DEFENCE, 100, 'damage / 2'));
        $beast = $this->createUnluckyBeast();
        $beast->prepareToAttack();
        $battleResult = new BattleResult($hero, $beast, $this->output);
        $beast->attack($hero);

        $this->output
            ->expects($this->exactly(6))
            ->method('writeln');

        $battleResult->getRoundResult(1);
    }

    public function createUnluckyHero()
    {
        $hero = new Hero(
            'Orderus',
            new Health(70, 100),
            new Strength(70, 80),
            new Defence(45, 55),
            new Speed(40, 50),
            new Luck(0, 0)
        );

        return $hero;
    }

    public function createLuckyHero()
    {
        $hero = new Hero(
            'Orderus',
            new Health(70, 100),
            new Strength(70, 80),
            new Defence(45, 55),
            new Speed(40, 50),
            new Luck(100, 100)
        );

        return $hero;
    }

    public function createUnluckyBeast()
    {
        return new Beast(
            'Wolf',
            new Health(60, 90),
            new Strength(60, 90),
            new Defence(40, 60),
            new Speed(40, 60),
            new Luck(0, 0)
        );
    }

    public function createLuckyBeast()
    {
        return new Beast(
            'Wolf',
            new Health(60, 90),
            new Strength(60, 90),
            new Defence(40, 60),
            new Speed(40, 60),
            new Luck(100, 100)
        );
    }
}