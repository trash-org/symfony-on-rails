<?php

namespace App\Rails\Eloquent\Command;

use App\Rails\Eloquent\Helper\MigrationService;
use php7extension\core\console\helpers\input\Question;
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
        $historyCollection = MigrationService::allForDown();
        $this->showClasses(ArrayHelper::getColumn($historyCollection, 'version'));
        $isApply = Question::confirm2('Down migrations?', false);

        if( ! $isApply) {
            return;
        }

        $this->runMigrate($historyCollection, 'down', $output);
    }

}
