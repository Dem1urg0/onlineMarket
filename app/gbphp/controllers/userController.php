<?php

namespace App\controllers;

use App\main\App;
use App\Repositories\UserRepository;

class userController extends Controller
{
    protected $userRepository;
    public function __construct($render, $request)
    {
        parent::__construct($render, $request);
        $this->userRepository = App::call()->UserRepository;
    }
    public function oneAction()
    {
        if (empty($id = $this->getRequest('id'))) {
            throw new \Exception('Не передан id пользователя', 400);
        }
        if (empty($user = $this->userRepository->getOne($id))) {
            throw new \Exception('Пользователь не найден', 404);
        }

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

    public function addAction()
    {
        return $this->render('register', []);
    }

    public function editAction()
    {
        $user = $this->getSession('user');
        if (!empty($user['id'])) {
            if (!empty($user = $this->userRepository->getOne($user['id']))) {
                $userData = [
                    'login' => $user->login,
                    'name' => $user->name,
                ];

                return $this->render('edit', ['userData' => $userData]);
            }
        } else {
            header('Location: /auth/');
        }
    }
}