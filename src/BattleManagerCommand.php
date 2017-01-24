<?php

namespace Iulyanp\Battle;

use Iulyanp\Battle\Entity\Beast;
use Iulyanp\Battle\Entity\Defence;
use Iulyanp\Battle\Entity\Health;
use Iulyanp\Battle\Entity\Hero;
use Iulyanp\Battle\Entity\Luck;
use Iulyanp\Battle\Entity\PlayerInterface;
use Iulyanp\Battle\Entity\Skill;
use Iulyanp\Battle\Entity\Speed;
use Iulyanp\Battle\Entity\Strength;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class BattleManagerCommand
 * @package Iulyanp\Battle
 */
class BattleManagerCommand extends Command
{
    use OutputTrait;

    const BATTLE_ROUNDS = 20;

    /**
     * @var InputInterface
     */
    private $input;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Configure the command
     */
    public function configure()
    {
        $this->setName('start-battle')
            ->setDescription('Start a new battle between a hero and a beast')
            ->setHelp(
                <<<EOT
    This command will start a battle between a hero and a beast
    If you want to add skills to the hero you can chose yes on the first question and
    configure the skill. 
    If you want to use only the default skills chose no as an answer.
    Usage:
    <info>php app.php start-battle</info>
EOT
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return bool
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $newSkill = $this->addNewSkill($input, $output);

        $hero = new Hero(
            'Orderus',
            new Health(70, 100),
            new Strength(70, 80),
            new Defence(45, 55),
            new Speed(40, 50),
            new Luck(10, 30)
        );
        $hero->addSkill(new Skill('rapid strike', Skill::ATTACK, 10, 'damage * 2'))
            ->addSkill(new Skill('magic shield', Skill::DEFENCE, 20, 'damage / 2'));

        if (isset($newSkill)) {
            $hero->addSkill($newSkill);
        }

        $beast = new Beast(
            'Wolf',
            new Health(60, 90),
            new Strength(60, 90),
            new Defence(40, 60),
            new Speed(40, 60),
            new Luck(25, 40)
        );

        $this->info($hero->__toString());
        $this->info($beast->__toString());
        $this->write('----------------------------------');

        $this->initBattle($hero, $beast);

        return true;
    }

    /**
     * @param PlayerInterface $hero
     * @param PlayerInterface $beast
     */
    public function initBattle(PlayerInterface $hero, PlayerInterface $beast)
    {
        $this->info('Battle start!');

        if ($hero->getSpeed()->value() > $beast->getSpeed()->value()) {
            $this->info(sprintf('%s starts the fight', $hero->getName()));

            $hero->prepareToAttack();
        }

        if ($beast->getSpeed()->value() > $hero->getSpeed()->value()) {
            $this->info(sprintf('%s starts the fight', $beast->getName()));

            $beast->prepareToAttack();
        }

        if ($hero->getSpeed()->value() === $beast->getSpeed()->value()) {
            $this->info(sprintf('%s and %s have same speed', $hero->getName(), $beast->getName()));
            if ($hero->getLuck()->value() > $beast->getLuck()->value()) {
                $this->comment(sprintf('%s is more lucky and starts the first attack', $hero->getName()));

                $hero->prepareToAttack();
            } else {
                $this->comment(sprintf('%s is more lucky and starts the first attack', $beast->getName()));

                $beast->prepareToAttack();
            }
        }

        $this->battle($hero, $beast);
    }

    /**
     * @param PlayerInterface $hero
     * @param PlayerInterface $beast
     */
    public function battle(PlayerInterface $hero, PlayerInterface $beast)
    {
        $i = 0;

        while (($hero->getHealth()->value() > 0 && $beast->getHealth()->value() > 0) && $i < self::BATTLE_ROUNDS) {

            $battleResult = new BattleResult($hero, $beast, $this->output);

            if ($hero->isReadyToAttack()) {
                $hero->attack($beast);
            } else {
                $beast->attack($hero);
            }

            $battleResult->getRoundResult(++$i);
        }

        return $battleResult->getResult();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Skill
     * @throws \Exception
     */
    protected function addNewSkill(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Do you want to add a new skill?',
            ['yes', 'no'],
            'no'
        );
        $question->setErrorMessage('Answer %s is invalid.');

        $createSkill = $helper->ask($input, $output, $question);

        if ('yes' == $createSkill) {
            $nameQuestion = new Question('Please enter a name: ');
            $name = $helper->ask($input, $output, $nameQuestion);
            if ('' == $name && !is_string($name)) {
                throw new \Exception('The name of the skill should be a string.');
            }

            $typeQuestion = new ChoiceQuestion(
                'What type of skill?',
                [
                    Skill::ATTACK  => 'attack',
                    Skill::DEFENCE => 'defence'
                ],
                Skill::ATTACK
            );
            $typeQuestion->setErrorMessage('Skill of type %s does not exists');
            $type = $helper->ask($input, $output, $typeQuestion);

            $probabilityQuestion = new ChoiceQuestion(
                'What probability should have this skill?',
                [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100],
                10
            );
            $probabilityQuestion->setErrorMessage('%s does not exists');
            $probability = $helper->ask($input, $output, $probabilityQuestion);

            $formulaQuestion = new Question('Damage formula for this skill: ');
            $formula = $helper->ask($input, $output, $formulaQuestion);

            $newSkill = new Skill($name, $type, $probability, $formula);

            return $newSkill;
        }

        return null;
    }
}
