<?php

namespace App\Command;

use php7extension\yii\helpers\FileHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    protected static $defaultName = 'orm:migrate1111';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Migration up')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $srcDir = __DIR__ . '/../../src/';
        $dir = 'Bundle\Article\Domain\Migration';
        $files = FileHelper::scanDir($srcDir . $dir);
        //dd($files);
        foreach ($files as $file) {
            $className = 'App\\' . $dir . '\\' . FileHelper::fileRemoveExt($file);
            $output->writeln([
                '=== Migrate - ' . $className,
            ]);

            require_once($srcDir . $dir . '/' . $file);
            $migration = new $className;

            $migration->up();

            //dd($className);
        }
    }

}
