<?php

namespace App\Entities;

/**
 * Класс сущности Пользователь
 */
class User extends Entity
{
    /**
     * id пользователя
     * @var int $id
     */
    public int $id;
    /**
     * Имя пользователя
     * @var string $name
     */
    public string $name = 'none';
    /**
     * Пароль пользователя
     * @var string $password
     */
    public string $password;
    /**
     * Логин пользователя
     * @var string $login
     */
    public string $login;
    /**
     * Роль пользователя
     * @var int $role
     */
    public int $role = 0;
}