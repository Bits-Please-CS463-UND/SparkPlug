<?php
declare(strict_types=1);

namespace App\Struct;

use App\Entity\User;

class SerializedUser
{
    public readonly string $id;
    public readonly string $firstName;
    public readonly string $lastName;
    public readonly string $email;
    public readonly string $phoneNum;

    public function __construct(User $user){
        $this->id = $user->id;
        $this->firstName = $user->firstName;
        $this->lastName = $user->lastName;
        $this->email = $user->email;
        $this->phoneNum = $user->phoneNum;
    }
}