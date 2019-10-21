<?php

namespace App\Rails\Eloquent\Command;

use App\Rails\Eloquent\Helper\MigrateHelper;
use php7extension\core\console\helpers\input\Question;
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
        $classes = MigrateHelper::getAll();
        asort($classes);
        // todo: intersect for NOT exists migrate in table
        $this->showClasses($classes);
        $isApply = Question::confirm2('Up migrations?', false);

        if( ! $isApply) {
            return;
        }

        $this->runMigrate($classes, 'up', $output);
    }

}
