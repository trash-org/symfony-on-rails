<?php

namespace App\Rails\Eloquent\Fixture\Command;

use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use Illuminate\Database\Eloquent\Collection;
use php7extension\core\console\helpers\input\Select;
use php7extension\core\console\helpers\Output;
use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixtureImportCommand extends BaseMigrateCommand
{
    protected static $defaultName = 'orm:fixture:import';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Import fixture data to DB')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FixtureEntity[]|Collection $tableCollection */
        $tableCollection = $this->fixtureService->allFixtures();
        $selectedTables = Select::display('Select tables for import', ArrayHelper::getColumn($tableCollection->toArray(), 'name'), 1);
        $selectedTables = array_values($selectedTables);

        $output->writeln('');

        foreach ($selectedTables as $tableName) {
            $this->fixtureService->importTable($tableName);
            $output->writeln(' * ' . $tableName);
        }

        $output->writeln(['', '<fg=green>All fixtures imported!</>', '']);
    }

}
