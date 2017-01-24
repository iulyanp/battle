<?php

namespace Iulyanp\Battle\Tests;

use Iulyanp\Battle\Entity\Skill;

class SkillTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    /**
     * @test
     */
    public function can_create_a_new_skill()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 100,
            'formula'     => 'damage * 2',
        ];

        $skill = new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);

        $this->assertEquals($skillData['name'], $skill->getName());
        $this->assertEquals($skillData['type'], $skill->getType());
        $this->assertEquals($skillData['probability'], $skill->getProbability());
        $this->assertEquals(str_replace('damage', '%s', $skillData['formula']), $skill->getFormula());
    }

    /**
     * @test
     */
    public function a_skill_with_probability_of_100_percent_will_always_be_active()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 100,
            'formula'     => 'damage * 2',
        ];

        $skill = new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);

        $this->assertEquals(true, $skill->isActive());
    }

    /**
     * @test
     */
    public function get_damage_returns_same_damage()
    {
        $defaultDamage = 10;
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 0,
            'formula'     => 'damage * 2',
        ];

        $skill = new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);

        $this->assertEquals(0, $skill->getDamageWithActiveSkills($defaultDamage));
    }

    /**
     * @test
     */
    public function can_not_create_skill_with_a_different_type_then_attak_or_defence()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => 'new type',
            'probability' => 100,
            'formula'     => 'damage * 2',
        ];

        $this->expectException('\Exception');

        new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);
    }

    /**
     * @test
     */
    public function can_not_create_skill_with_probability_higher_then_100_percent()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 101,
            'formula'     => 'damage * 2',
        ];

        $this->expectException('\Exception');
        $this->expectExceptionMessage('Skill probability can be only numeric from 0 to 100.');

        new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);
    }

    /**
     * @test
     *
     * ex. damage * 2
     *     damage / 4
     *     damage + 2
     *     damage - 2
     * @dataProvider addDataProvider
     */
    public function can_not_create_skill_with_wrong_damage_formula($name, $type, $probability,$formula)
    {
        $this->expectException('\Exception');

        new Skill($name, $type, $probability, $formula);
    }

    /**
     * @test
     */
    public function get_damage_result_when_default_damage_divides_to_10()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 100,
            'formula'     => 'damage / 10',
        ];

        $skill = new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);

        $damage = $skill->getDamageWithActiveSkills(20);

        $this->assertEquals(2, $damage);
    }

    /**
     * @test
     */
    public function get_damage_result_when_multiply_with_2_to_default_damage()
    {
        $skillData = [
            'name'        => 'test_skill',
            'type'        => Skill::ATTACK,
            'probability' => 100,
            'formula'     => 'damage * 2',
        ];

        $skill = new Skill($skillData['name'], $skillData['type'], $skillData['probability'], $skillData['formula']);

        $damage = $skill->getDamageWithActiveSkills(10);

        $this->assertEquals(20, $damage);
    }

    public function addDataProvider()
    {
        return [
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => 'damage ** 5',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => 'damage . 5',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => 'damage',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => '* 2',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => 'damage 2 +',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => 'test - 2',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => '12 * :damage',
            ],
            [
                'name'        => 'test_skill',
                'type'        => Skill::ATTACK,
                'probability' => 20,
                'formula'     => '12 * damage * 2',
            ],
        ];

    }
}