<?php

namespace App\Rails\Eloquent\Fixture\Command;

use App\Rails\Domain\Data\Collection;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use php7extension\core\console\helpers\input\Select;
use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends BaseCommand
{
    protected static $defaultName = 'orm:fixture:export';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Export fixture data to files')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        /** @var FixtureEntity[]|Collection $tableCollection */
        $tableCollection = $this->fixtureService->allTables();

        $selectedTables = Select::display('Select tables for export', ArrayHelper::getColumn($tableCollection->toArray(), 'name'), 1);
        $selectedTables = array_values($selectedTables);

        $output->writeln('');

        foreach ($selectedTables as $tableName) {
            $this->fixtureService->exportTable($tableName);
            $output->writeln(' * ' . $tableName);
        }

        $output->writeln(['', '<fg=green>All fixtures exported!</>', '']);
    }

}
