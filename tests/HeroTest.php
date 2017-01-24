<?php

namespace Iulyanp\Battle\Tests;

use Iulyanp\Battle\Entity\Beast;
use Iulyanp\Battle\Entity\Defence;
use Iulyanp\Battle\Entity\Health;
use Iulyanp\Battle\Entity\Hero;
use Iulyanp\Battle\Entity\Luck;
use Iulyanp\Battle\Entity\Skill;
use Iulyanp\Battle\Entity\Speed;
use Iulyanp\Battle\Entity\Strength;

/**
 * Class PlayerTest
 * @package Iulyanp\Battle\Tests
 */
class HeroTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    /**
     * @test
     */
    public function was_lucky_returns_true()
    {
        $hero = $this->createLuckyHero();
        $hero->isLucky();

        $this->assertTrue($hero->wasLucky());
    }

    /**
     * @test
     */
    public function was_lucky_returns_false()
    {
        $hero = $this->createUnluckyHero();
        $hero->isLucky();

        $this->assertFalse($hero->wasLucky());
    }

    /**
     * @test
     */
    public function calculate_health_left()
    {
        $hero = $this->createLuckyHero();
        $health = $hero->getHealth()->value();
        $damage = 10;
        $healthLeft = $health - $damage;
        $hero->setDamage($damage);
        $hero->calculateHeathLeft();

        $this->assertEquals($healthLeft, $hero->getHealth()->value());
    }

    /**
     * @test
     */
    public function calculate_health_returns_zero_when_damage_is_bigger_than_healt()
    {
        $hero = $this->createUnluckyHero();
        $health = $hero->getHealth()->value();
        $damage = $health + 1;
        $hero->setDamage($damage);
        $hero->calculateHeathLeft();

        $this->assertEquals(0, $hero->getHealth()->value());
    }

    /**
     * @test
     */
    public function hero_ready_to_attack_when_prepare_to_attack_is_true()
    {
        $hero = $this->createUnluckyHero();
        $hero->prepareToAttack();

        $this->assertTrue($hero->isReadyToAttack());
    }

    /**
     * @test
     */
    public function hero_ready_to_attack_when_prepare_to_attack_is_false()
    {
        $hero = $this->createUnluckyHero();
        $hero->prepareToAttack(false);

        $this->assertFalse($hero->isReadyToAttack());
    }

    /**
     * @test
     */
    public function calculate_default_damage()
    {
        $hero = $this->createUnluckyHero();
        $defence = $hero->getDefence()->value();
        $strength = 100;
        $defaultDamage = $strength - $defence;

        $this->assertEquals($defaultDamage, $hero->calculateDefaultDamage(100));
    }

    /**
     * @test
     */
    public function will_add_a_skill_to_hero()
    {
        $skill = new Skill('test', Skill::ATTACK, 10, 'damage * 2');
        $hero = $this->createUnluckyHero();
        $hero->addSkill($skill);

        $this->assertCount(1, $hero->getSkills());
    }

    /**
     * @test
     */
    public function hero_attack_will_damage_defender_with_the_difference_between_his_strength_and_defender_defence()
    {
        $hero = $this->createUnluckyHero();
        $beast = $this->createUnluckyBeast();
        $damage = $hero->getStrength()->value() - $beast->getDefence()->value();
        $healthLeft = $beast->getHealth()->value() - $damage;

        $hero->prepareToAttack()->attack($beast);

        $this->assertTrue($beast->isReadyToAttack());
        $this->assertFalse($hero->isReadyToAttack());
        $this->assertEquals($healthLeft, $beast->getHealth()->value());
    }

    /**
     * @test
     */
    public function beast_will_have_same_health_when_is_lucky_when_hero_attack()
    {
        $hero = $this->createUnluckyHero();
        $beast = $this->createLuckyBeast();
        $health = $beast->getHealth()->value();

        $hero->prepareToAttack()->attack($beast);

        $this->assertEquals($health, $beast->calculateHeathLeft()->getHealth()->value());
    }

    /**
     * @test
     */
    public function hero_attack_with_skill()
    {
        $hero = $this->createUnluckyHero();
        $hero->addSkill(new Skill('test', Skill::ATTACK, 100, 'damage * 2'));
        $beast = $this->createUnluckyBeast();
        $defaultDamage = $beast->calculateDefaultDamage($hero->getStrength()->value());

        $hero->attack($beast);

        $this->assertEquals(2 * $defaultDamage, $beast->getDamage());
    }

    /**
     * @test
     */
    public function hero_defend_with_skill_on_beast_attack()
    {
        $hero = $this->createUnluckyHero();
        $hero->addSkill(new Skill('test', Skill::DEFENCE, 100, 'damage / 2'));
        $beast = $this->createUnluckyBeast();
        $defaultDamage = $hero->calculateDefaultDamage($beast->getStrength()->value());

        $beast->attack($hero);

        $this->assertEquals($defaultDamage / 2, $hero->getDamage());
    }

    /**
     * @test
     */
    public function create_hero_thow_error_if_name_is_not_string()
    {
        $this->expectException('\Exception');
        new Hero(
            200,
            new Health(100, 100),
            new Strength(0, 0),
            new Defence(10, 10),
            new Speed(50, 50),
            new Luck(100, 100)
        );
    }

    /**
     * @test
     */
    public function to_string_return_a_string()
    {
        $hero = new Hero(
            'Orderus',
            new Health(100, 100),
            new Strength(0, 0),
            new Defence(10, 10),
            new Speed(50, 50),
            new Luck(100, 100)
        );

        $this->assertEquals(
            'Orderus: Speed:50 Strength:0 Health:100, Damage:10, Luck:100',
            $hero->__toString()
        );
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
}