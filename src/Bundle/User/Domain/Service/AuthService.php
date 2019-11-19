<?php

namespace App\Bundle\User\Domain\Service;

use App\Bundle\User\Domain\Entity\User;
use App\Bundle\User\Domain\Form\AuthForm;
use App\Bundle\User\Domain\Repositories\Config\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use php7extension\core\common\helpers\StringHelper;
use php7extension\yii\base\Security;
use PhpLab\Domain\Data\Collection;
use PhpLab\Domain\Exceptions\UnauthorizedException;
use PhpLab\Domain\Exceptions\UnprocessibleEntityException;
use PhpLab\Rest\Entity\ValidateErrorEntity;
use FOS\UserBundle\Model\UserManagerInterface;
use php7extension\crypt\domain\entities\JwtEntity;

class AuthService
{

    private $em;
    private $userManager;
    private $security;
    private $jwtService;

    public function __construct(EntityManagerInterface $em, UserManagerInterface $userManager)
    {
        $this->em = $em;
        $this->userManager = $userManager;
        $this->security = new Security;
        $this->jwtService = new JwtService(new ProfileRepository);
    }

    public function info(): UserInterface
    {
        /** @var User $userEntity */
        $userEntity = $this->userManager->findUserByUsername('user1');
        if(empty($userEntity)) {
            $exception = new UnauthorizedException;
            throw $exception;
        }
        return $userEntity;
    }

    public function authentication(AuthForm $form): UserInterface
    {
        /** @var User $userEntity */
        $userEntity = $this->userManager->findUserByUsername($form->login);
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
        $token = $this->forgeToken($userEntity);
        //$token = StringHelper::generateRandomString(64);
        $userEntity->setApiToken($token);
        return $userEntity;
    }

    private function forgeToken(UserInterface $userEntity) {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = ['id' => $userEntity->getId()];
        $token = 'jwt ' . $this->jwtService->sign($jwtEntity, 'auth');
        return $token;
    }
}