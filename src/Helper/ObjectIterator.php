<?php

namespace App\Helper;

use Symfony\Component\Security\Core\User\UserInterface;

class ObjectIterator
{
    private $id;
    private $firstname;
    private $lastname;
    private $mobile;
    private $email;
    public function __construct(UserInterface $user)
    {
        $this->id = $user->getId();
        $this->firstname = $user->getFirstname();
        $this->lastname = $user->getLastname();
        $this->mobile = $user->getMobile();
        $this->email = $user->getEmail();
    }

    public function all()
    {
        return get_object_vars($this);
    }
}