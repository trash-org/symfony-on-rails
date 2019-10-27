<?php

namespace App\Bundle\User\Domain\Service;

use App\Bundle\User\Domain\Entity\Identity;
use App\Bundle\User\Domain\Form\AuthForm;

class AuthService
{

    public function authentication(AuthForm $form) : Identity {
        $identity = new Identity;
        $identity->id = 1;
        $identity->login = 'admin';
        $identity->token = '1234567890987654321';
        return $identity;
    }

}