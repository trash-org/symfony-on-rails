<?php

namespace App\Rails\Eloquent\Migration\Command;

use App\Rails\Eloquent\Migration\Entity\MigrationEntity;
use App\Rails\Eloquent\Migration\Helper\MigrationService;
use php7extension\core\console\helpers\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

abstract class BaseMigrateCommand extends Command
{

    protected $migrationService;

    public function __construct(?string $name = null)
    {
        parent::__construct($name);
        $this->migrationService = new \App\Rails\Eloquent\Migration\Service\MigrationService();
    }

    protected function showClasses($classes) {
        Output::line();
        Output::line('Migrations:');
        Output::arr(array_values($classes));
        Output::line();
    }

    protected function isContinueQuestion(string $question, InputInterface $input, OutputInterface $output) : bool {
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
