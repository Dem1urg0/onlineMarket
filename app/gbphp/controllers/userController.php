<?php

namespace App\controllers;

use App\main\App;
use App\Middleware\AuthMiddleware;
use App\Repositories\UserRepository;
use App\validators\UserValidator;

/**
 * Контроллер пользователей
 *
 * Class userController
 * @package App\controllers
 */
class userController extends Controller
{
    /**
     * Репозиторий пользователей
     * @var UserRepository
     */
    protected UserRepository $userRepository;
    /**
     * Валидатор пользователей
     * @var UserValidator
     */
    protected UserValidator $userValidator;
    /**
     * Middleware для аутентификации
     * @var AuthMiddleware
     */
    protected AuthMiddleware $authMiddleware;

    /**
     * Конструктор контроллера
     *
     * @param $render - экземпляр класса Render
     * @param $request - экземпляр класса Request
     */
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->userRepository = App::call()->UserRepository;
        $this->userValidator = App::call()->UserValidator;
        $this->authMiddleware = App::call()->AuthMiddleware;
    }

    /**
     * Выводит одного пользователя по id
     * @return string
     */
    public function oneAction()
    {
        $this->userValidator->validateGet();

        $this->userValidator->checkEmpty($id = $this->getRequest('id'));

        $this->userValidator->checkNumeric($id);

        $user = $this->userValidator->checkUserById($id);

        $userData = [
            'id' => $id,
            'login' => $user->login,
            'name' => $user->name,
            'role' => $user->role,
        ];

        return $this->render(
            'user',
            [
                'userData' => $userData
            ]);
    }

    /**
     * Добавляет пользователя
     * @return string
     */
    public function addAction()
    {
        return $this->render('register', []);
    }

    /**
     * Редактирует пользователя
     * @return string
     */
    public function editAction()
    {
        $this->authMiddleware->checkAuth();

        $user = $this->userValidator->checkUserById($this->getSession('user')['id']);

        $userData = [
            'login' => $user->login,
            'name' => $user->name,
        ];

        return $this->render('edit', ['userData' => $userData]);

    }
}