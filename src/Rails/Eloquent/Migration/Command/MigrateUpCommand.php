<?php

namespace App\Rails\Eloquent\Migration\Command;

use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateUpCommand extends BaseMigrateCommand
{
    protected static $defaultName = 'orm:migrate:up';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Migration up')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command up all migrations...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filteredCollection = $this->migrationService->allForUp();
        if(empty($filteredCollection)) {
            $output->writeln(['', '<fg=magenta>- Migrations up to date! -</>', '']);
            return;
        }

        $this->showClasses(ArrayHelper::getColumn($filteredCollection, 'version'));
        if ( ! $this->isContinueQuestion('Apply migrations?', $input, $output)) {
            return;
        }

        $outputInfoCallback = function ($version) use ($output) {
            $output->writeln(' * ' . $version);
        };
        $output->writeln('');
        $this->runMigrate($filteredCollection, 'up', $outputInfoCallback);
        $output->writeln(['', '<fg=green>All migrations success!</>', '']);
    }

}
