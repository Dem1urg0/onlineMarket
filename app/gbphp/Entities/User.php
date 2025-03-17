<?php

namespace App\Entities;

class User extends Entity
{
    public $id;
    public $name = 'none';
    public $password;
    public $login;
    public $role = 0;
}