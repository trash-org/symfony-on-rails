<?php

namespace App\Bundle\User\Domain\Service;

use App\Bundle\User\Domain\Repositories\Config\ProfileRepository;
use php7extension\core\traits\classAttribute\MagicSetTrait;
use php7extension\crypt\domain\entities\JwtEntity;
use php7extension\crypt\domain\helpers\JwtEncodeHelper;
use php7extension\crypt\domain\helpers\JwtHelper;
use php7extension\crypt\domain\libs\ProfileContainer;

class JwtService {

    use MagicSetTrait;

    private $profileRepository;

    public function sign(JwtEntity $jwtEntity, string $profileName) : string {
        $profileEntity = $this->profileRepository->oneByName($profileName);
        //print_r($profileEntity);exit;

        //print_r($profileEntity);exit;
        $token = JwtHelper::sign($jwtEntity, $profileEntity);
        return $token;
    }

    public function verify(string $token, string $profileName) : JwtEntity {
        $profileEntity = $this->profileRepository->oneByName($profileName);
        $jwtEntity = JwtHelper::decode($token, $profileEntity);
        return $jwtEntity;
    }

    public function decode(string $token) {
        $jwtEntity = JwtEncodeHelper::decode($token);
        return $jwtEntity;
    }

    public function setProfiles($profiles)
    {
        if(is_array($profiles)) {
            $this->profileContainer = new ProfileContainer($profiles);
        } else {
            $this->profileContainer = $profiles;
        }
    }

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

}
