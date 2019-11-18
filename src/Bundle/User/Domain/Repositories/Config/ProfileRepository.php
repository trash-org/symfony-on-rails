<?php

namespace App\Bundle\User\Domain\Repositories\Config;

use php7extension\core\enums\TimeEnum;
use php7extension\crypt\domain\entities\JwtProfileEntity;
use php7extension\crypt\domain\entities\KeyEntity;

class ProfileRepository
{

    public function oneByName(string $profileName)  {
        $profileEntity = new JwtProfileEntity;
        $profileEntity->name = $profileName;
        $profileEntity->key = new KeyEntity;
        $profileEntity->key->private = 'W4PpvVwI82Rfl9fl2R9XeRqBI0VFBHP3';
        $profileEntity->life_time = TimeEnum::SECOND_PER_YEAR;
        return $profileEntity;
    }

}