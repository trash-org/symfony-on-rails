<?php

namespace App\Rails\Eloquent\Command;

use App\Rails\Eloquent\Migrate\BaseCreateTableMigrate;
use php7extension\core\common\helpers\ClassHelper;
use php7extension\core\console\helpers\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseMigrateCommand extends Command
{

    protected function showClasses($classes) {
        Output::line();
        Output::line('Migrations:');
        Output::arr(array_values($classes));
    }

    protected function runMigrate($classes, $method, OutputInterface $output) {
        Output::line();
        foreach ($classes as $class => $classNameClean) {
            /** @var BaseCreateTableMigrate $migration */
            $migration = new $class;
            $output->writeln([
                ' * ' . ClassHelper::getClassOfClassName($class),
            ]);
            $migration->{$method}();
            if($method == 'up') {
                // todo: register to migration table
            } else {
                // todo: un register to migration table
            }
        }
        $output->writeln([
            '',
            'All migrations success!',
        ], OutputInterface::OUTPUT_NORMAL);
    }

}
