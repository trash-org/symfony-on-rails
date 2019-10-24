<?php

namespace App\Rails\Eloquent\Fixture\Command;

use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use App\Rails\Eloquent\Fixture\Service\FixtureService;
use php7extension\core\console\helpers\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class BaseMigrateCommand extends Command
{

    protected $fixtureService;

    public function __construct(?string $name = null, FixtureService $fixtureService)
    {
        parent::__construct($name);
        $this->fixtureService = $fixtureService;
    }

    protected function showClasses($classes) {
        Output::line();
        Output::line('Fixtures:');
        Output::arr(array_values($classes));
        Output::line();
    }

    protected function isContinueQuestion(string $question, InputInterface $input, OutputInterface $output) : bool {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion($question . ' (y|n) [n]: ', false);
        return $helper->ask($input, $output, $question);
    }

    protected function runMigrate($collection, $method, $outputInfoCallback) {
        /** @var FixtureEntity[] $collection */
        foreach ($collection as $fixtureEntity) {
            if($method == 'up') {
                $this->fixtureService->upMigration($fixtureEntity);
            } else {
                $this->fixtureService->downMigration($fixtureEntity);
            }
            call_user_func($outputInfoCallback, $fixtureEntity->version);
        }
    }

}
