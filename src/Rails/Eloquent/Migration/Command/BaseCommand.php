<?php

namespace App\Rails\Eloquent\Migration\Command;

use App\Rails\Eloquent\Migration\Entity\MigrationEntity;
use App\Rails\Eloquent\Migration\Service\MigrationService;
use php7extension\core\console\helpers\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class BaseCommand extends Command
{

    protected $migrationService;

    public function __construct(?string $name = null, MigrationService $migrationService)
    {
        parent::__construct($name);
        $this->migrationService = $migrationService;
    }

    protected function configure()
    {
        $this
            ->addOption(
                'withConfirm',
                null,
                InputOption::VALUE_REQUIRED,
                'Your selection migrations',
                true
            );
    }

    protected function showClasses($classes) {

    }

    protected function isContinueQuestion(string $question, InputInterface $input, OutputInterface $output) : bool {
        $withConfirm = $input->getOption('withConfirm');
        if( ! $withConfirm) {
            return true;
        }
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion($question . ' (y|n) [n]: ', false);
        return $helper->ask($input, $output, $question);
    }

    protected function runMigrate($collection, $method, $outputInfoCallback) {
        /** @var MigrationEntity[] $collection */
        foreach ($collection as $migrationEntity) {
            if($method == 'up') {
                $this->migrationService->upMigration($migrationEntity);
            } else {
                $this->migrationService->downMigration($migrationEntity);
            }
            call_user_func($outputInfoCallback, $migrationEntity->version);
        }
    }

}
