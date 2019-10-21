<?php

namespace App\Rails\Eloquent\Command;

use App\Rails\Eloquent\Entity\MigrationEntity;
use App\Rails\Eloquent\Helper\MigrateHistoryHelper;
use App\Rails\Eloquent\Helper\MigrationService;
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

    protected function runMigrate($collection, $method, OutputInterface $output) {
        Output::line();
        /** @var MigrationEntity[] $collection */
        foreach ($collection as $migrationEntity) {

            if($method == 'up') {
                MigrationService::upMigration($migrationEntity);
            } else {
                MigrationService::downMigration($migrationEntity);
            }

            $output->writeln([
                ' * ' . ClassHelper::getClassOfClassName($migrationEntity->version),
            ]);

        }
        $output->writeln([
            '',
            'All migrations success!',
        ], OutputInterface::OUTPUT_NORMAL);
    }

}
