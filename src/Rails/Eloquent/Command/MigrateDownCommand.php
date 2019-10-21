<?php

namespace App\Rails\Eloquent\Command;

use App\Rails\Eloquent\Helper\MigrateHelper;
use php7extension\core\console\helpers\input\Question;
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
        $classes = MigrateHelper::getAll();
        arsort($classes);
        // todo: intersect for exists migrate in table
        $this->showClasses($classes);
        $isApply = Question::confirm2('Down migrations?', false);

        if( ! $isApply) {
            return;
        }

        $this->runMigrate($classes, 'down', $output);
    }

}
