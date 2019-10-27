<?php

namespace App\Rails\Eloquent\Db\Command;

use App\Rails\Domain\Data\Collection;
use App\Rails\Eloquent\Fixture\Entity\FixtureEntity;
use php7extension\core\console\helpers\input\Select;
use php7extension\core\console\helpers\Output;
use php7extension\yii\helpers\ArrayHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCommand extends BaseCommand
{
    protected static $defaultName = 'orm:db:delete-all-tables';

    protected function configure()
    {
        parent::configure();
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Delete all tables')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['<fg=white># DELETE all tables</>']);

        /** @var FixtureEntity[]|Collection $tableCollection */
        $tableCollection = $this->fixtureService->allForDelete();

        $withConfirm = $input->getOption('withConfirm');
        if($withConfirm) {
            $versionArray = ArrayHelper::getColumn($tableCollection, 'name');
            $versionArray = array_values($versionArray);
            Output::line();
            Output::arr($versionArray, 'Tables for delete');
            Output::line();
        }

        if ( ! $this->isContinueQuestion('Sure DELETE all tables?', $input, $output)) {
            return;
        }

        $output->writeln('');

        foreach ($tableCollection as $fixtureEntity) {
            $this->fixtureService->dropTable($fixtureEntity->name);
            $output->writeln(' * ' . $fixtureEntity->name);
        }

        $output->writeln(['', '<fg=green>DELETE all tables success!</>', '']);
    }

}
