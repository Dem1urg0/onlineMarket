<?php

namespace App\tests;

use App\Entities\User;
use PHPUnit\Framework\TestCase;
use App\services\UserService;

class UserServiceTest extends TestCase
{
    /**
     * @param $login
     * @param $password
     * @param $expected
     * @return void
     * @throws \ReflectionException
     * @dataProvider dataForTestHasErrorForAdd
     */
    public function testHasErrorForAdd($login, $password, $expected)
    {
        $reflectUserService = new \ReflectionClass(UserService::class);
        $reflectionHasErrorForAdd = $reflectUserService->getMethod('hasErrorForAdd');
        $reflectionHasErrorForAdd->setAccessible(true);

        $result = $reflectionHasErrorForAdd->invoke(new UserService(), ['login' => $login, 'password' => $password]);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestHasErrorForAdd()
    {
        return [
            ['login', 'password', false],
            ['', 'password', true],
            ['login', '', true],
            ['', '', true],
        ];
    }

    /**
     * @param $login
     * @param $password
     * @param $id
     * @param $expected
     * @return void
     * @throws \ReflectionException
     * @dataProvider dataForTestHasErrorForUpdate
     */
    public function testHasErrorForUpdate($login, $password, $id, $expected)
    {
        $reflectionUserService = new \ReflectionClass(UserService::class);
        $reflectionHasErrorForUpdate = $reflectionUserService->getMethod('hasErrorForUpdate');
        $reflectionHasErrorForUpdate->setAccessible(true);

        $result = $reflectionHasErrorForUpdate->invoke(new UserService(), ['login' => $login, 'password' => $password, 'id' => $id]);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestHasErrorForUpdate()
    {
        return [
            ['login', 'password', 'id', false],
            ['', 'password', 'id', false],
            ['login', '', 'id', false],
            ['', '', 'id', true],
            ['login', 'password', '', true],
            ['', 'password', '', true],
            ['login', '', '', true],
            ['', '', '', true],
        ];
    }

    /**
     * @param $login
     * @param $user
     * @param $expected
     * @return void
     * @dataProvider dataForTestValidateLogin
     */
    public function testValidateLogin($login, $user, $expected)
    {
        $mockUserService = $this->getMockBuilder(UserService::class)
            ->onlyMethods(['getUserByLogin'])
            ->getMock();

        $mockUserService->method('getUserByLogin')
            ->willReturn($user);

        $result = $mockUserService->validateLogin($login);
        $this->assertEquals($expected, $result);
    }

    public function dataForTestValidateLogin()
    {
        return [
            ['', null, false],
            ['ab', null, false],
            ['valid_login', null, true],
            ['valid_login', ['id' => 1], false],
            ['login123', null, true],
            ['invalid-login', null, false]
        ];
    }

    /**
     * @param $password
     * @param $expected
     * @return void
     * @dataProvider dataForTestValidatePassword
     */
    public function testValidatePassword($password, $expected)
    {
        $userService = new UserService();
        $result = $userService->validatePassword($password);
        $this->assertEquals($expected, $result);
    }

    public function dataForTestValidatePassword()
    {
        return [
            ['', false],
            ['abc1', false],
            ['A1bcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ12345', false],
            ['abcd1234', false],
            ['Abcdefgh', false],
            ['Abc123', true],
            ['Abc 123', true],
            ['A1bcde', true],
        ];
    }

    /**
     * @param $params
     * @param $expected
     * @return void
     * @dataProvider dataForTestUpdateUser
     */
    public function testUpdateUser($params, $expected)
    {
        $mockUser = $this->createMock(User::class);

        $mockUserService = $this->getMockBuilder(UserService::class)
            ->onlyMethods(['getUserById', 'userSave', 'getUserByLogin'])->getMock();

        $mockUserService->method('getUserById')->willReturn($mockUser);
        $mockUserService->method('getUserByLogin')->willReturn(null);
        $mockUserService->method('userSave')->willReturn(true);

        $result = $mockUserService->updateUser((['login' => $params['login'], 'password' => $params['password'], 'id' => $params['id']]));
        $this->assertEquals($expected, $result);

    }

    public function dataForTestUpdateUser()
    {
        return [
            [
                ['login' => 'login', 'password' => 'Abc123', 'id' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => 'login', 'password' => null, 'id' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => 'Abc123', 'id' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => null, 'id' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => null, 'id' => 1],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => 'login', 'password' => 'Abc123', 'id' => 1],
                [
                    'msg' => 'Пользователь сохранен',
                    'success' => true,
                ]
            ],
            [
                ['login' => 'login', 'password' => null, 'id' => 1],
                [
                    'msg' => 'Пользователь сохранен',
                    'success' => true,
                ]
            ],
            [
                ['login' => null, 'password' => 'Abc123', 'id' => 1],
                [
                    'msg' => 'Пользователь сохранен',
                    'success' => true,
                ]
            ],
            //не валидный логины
            [
                ['login' => 'lo', 'password' => 'Abc123', 'id' => 1],
                [
                    'msg' => 'Логин не валиден',
                    'success' => false,
                ]
            ],
            [
                ['login' => 'lo', 'password' => null, 'id' => 1],
                [
                    'msg' => 'Логин не валиден',
                    'success' => false,
                ]
            ],
            //не валидный пароль
            [
                ['login' => 'login', 'password' => 'Ab', 'id' => 1],
                [
                    'msg' => 'Пароль не валиден',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => 'Ab', 'id' => 1],
                [
                    'msg' => 'Пароль не валиден',
                    'success' => false,
                ]
            ],
        ];
    }

    /**
     * @param $params
     * @param $expected
     * @return void
     * @dataProvider dataForTestAddUser
     */
    public function testAddUser($params, $expected)
    {
        $mockUser = $this->createMock(User::class);
        $mockUserService = $this->getMockBuilder(UserService::class)
            ->onlyMethods(['userSave', 'getUserByLogin', 'getUserObj'])->getMock();

        $mockUserService->method('getUserByLogin')->willReturn(null);
        $mockUserService->method('getUserObj')->willReturn($mockUser);

        $result = $mockUserService->addUser($params);

        $this->assertEquals($expected, $result);
    }

    public function dataForTestAddUser()
    {
        return [
            [
                ['login' => 'login', 'password' => 'Abc123'],
                [
                    'msg' => 'Пользователь сохранен',
                    'success' => true,
                ]
            ],
            [
                ['login' => 'login', 'password' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => 'Abc123'],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            [
                ['login' => null, 'password' => null],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ]
            ],
            //не валидный логины
            [
                ['login' => 'lo', 'password' => 'Abc123'],
                [
                    'msg' => 'Логин не валиден',
                    'success' => false,
                ]
            ],
            //не валидный пароль
            [
                ['login' => 'login', 'password' => 'Ab', 'id' => 1],
                [
                    'msg' => 'Пароль не валиден',
                    'success' => false,
                ]
            ],
        ];
    }
}