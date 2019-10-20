<?php

namespace App\Rails\Domain\Eloquent\Command;

use App\Rails\Domain\Eloquent\Base\BaseCreateTableMigrate;
use php7extension\core\common\helpers\ClassHelper;
use php7extension\core\console\helpers\Output;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseMigrateCommand extends Command
{

    protected function showClasses($classes) {
        Output::line();
        Output::line('Migrations:');
        Output::arr($classes);
    }

    protected function runMigrate($classes, $method, OutputInterface $output) {
        foreach ($classes as $class) {
            /** @var BaseCreateTableMigrate $migration */
            $migration = new $class;
            $output->writeln([
                '',
                ' * ' . ClassHelper::getClassOfClassName($class),
            ], OutputInterface::OUTPUT_NORMAL);
            $migration->{$method}();
        }
        $output->writeln([
            '',
            'All migrations success!',
        ], OutputInterface::OUTPUT_NORMAL);
    }

}
