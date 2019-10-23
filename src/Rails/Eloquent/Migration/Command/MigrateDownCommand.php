<?php

namespace App\Rails\Eloquent\Migration\Command;

use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateDownCommand extends BaseMigrateCommand
{
    protected static $defaultName = 'orm:migrate:down';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Migration down')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command down all migrations...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $historyCollection = $this->migrationService->allForDown();
        if(empty($historyCollection)) {
            $output->writeln(['', '<fg=magenta>- No applied migrations found! -</>', '']);
            return;
        }

        $this->showClasses(ArrayHelper::getColumn($historyCollection, 'version'));
        if ( ! $this->isContinueQuestion('Down migrations?', $input, $output)) {
            return;
        }

        $outputInfoCallback = function ($version) use ($output) {
            $output->writeln(' * ' . $version);
        };
        $output->writeln('');
        $this->runMigrate($historyCollection, 'down', $outputInfoCallback);
        $output->writeln(['', '<fg=green>All migrations success!</>', '']);
    }

}