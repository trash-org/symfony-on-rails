<?php

namespace App\Rails\Domain\Eloquent\Helper;

use php7extension\yii\helpers\ArrayHelper;
use php7extension\yii\helpers\FileHelper;

class MigrateHelper
{

    public static function getAll()
    {
        $directories = self::getConfig()['directory'];
        $classes = [];
        foreach ($directories as $dir) {
            $newClasses = self::scanDir($dir);
            $classes = ArrayHelper::merge($classes, $newClasses);
        }
        return $classes;
    }

    private static function getRootPath()
    {
        $rootDir = __DIR__ . '/../../../../../';
        return $rootDir;
    }

    private static function getConfig()
    {
        $rootDir = self::getRootPath();
        return include($rootDir . 'config/eloquent/migrate.php');
    }

    private static function scanDir($dir)
    {
        $srcDir = self::getRootPath() . 'src/';
        $files = FileHelper::scanDir($srcDir . $dir);
        $classes = [];
        foreach ($files as $file) {
            $classNameClean = FileHelper::fileRemoveExt($file);
            $className = 'App\\' . $dir . '\\' . $classNameClean;
            $classes[$className] = $classNameClean;
        }
        return $classes;
    }

}