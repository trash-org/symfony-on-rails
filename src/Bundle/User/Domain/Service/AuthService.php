<?php

namespace App\Bundle\User\Domain\Service;

use App\Bundle\User\Domain\Entity\User;
use App\Bundle\User\Domain\Form\AuthForm;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager;
use php7extension\yii\base\Security;
use PhpLab\Domain\Data\Collection;
use PhpLab\Domain\Exceptions\UnauthorizedException;
use PhpLab\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Rest\Entity\ValidateErrorEntity;
use FOS\UserBundle\Model\UserManagerInterface;

class AuthService
{

    private $em;
    private $userManager;
    private $security;

    public function __construct(EntityManagerInterface $em, UserManagerInterface $userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->security = new Security;
    }

    public function info(): User
    {
        //$repository = $this->getRepository();
        /** @var User $userEntity */
        //$userEntity = $repository->findOneBy(['username' => 'user1']);
        $userEntity = $this->userManager->findUserByUsername('user1');
        if(empty($userEntity)) {
            $exception = new UnauthorizedException;
            throw $exception;
        }
        //print_r($userEntity);exit;
        return $userEntity;
    }

    public function authentication(AuthForm $form): User
    {
        //$repository = $this->getRepository();
        /** @var User $userEntity */
        $userEntity = $this->userManager->findUserByUsername($form->login);
        //$userEntity = $repository->findOneBy(['username' => $form->login]);
        if(empty($userEntity)) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('login');
            $validateErrorEntity->setMessage('User not found');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
        $isValidPassword = $this->security->validatePassword($form->password, $userEntity->getPassword());
        if( ! $isValidPassword) {
            $errorCollection = new Collection;
            $validateErrorEntity = new ValidateErrorEntity;
            $validateErrorEntity->setField('password');
            $validateErrorEntity->setMessage('Bad password');
            $errorCollection->add($validateErrorEntity);
            $exception = new UnprocessibleEntityException;
            $exception->setErrorCollection($errorCollection);
            throw $exception;
        }
        return $userEntity;
    }

    /*private function getRepository() : ObjectRepository {
        $repository = $this->em->getRepository(User::class);
        return $repository;
    }*/

}