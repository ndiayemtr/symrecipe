<?php

namespace App\EntityListenner;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener 
{
    private UserPasswordHasherInterface $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function prePersist(User $user){
        $this->encodePassword($user);

    }

    public function preUpdate(User $user){
        $this->encodePassword($user);
        
    }

    /* public function encodePassword(User $user){
        if ($user->getPlainPassword() === null) {
            return;
        }
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $user->getPlainPassword()
            )
            );
            z
         
    } */

    public function encodePassword(User $user){
        if (empty($user->getPassword())) {
            return;
        }
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            )
            );
         
    }
    
}
