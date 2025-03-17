<?php

namespace App\tests;

use App\Entities\User;
use App\services\AuthService;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    /**
     * @param $login
     * @param $password
     * @param $expected
     * @return void
     * @throws \ReflectionException
     * @dataProvider dataForHasError
     */
    public function testHasError($login, $password, $expected)
    {
        $reflectionAuthService = new \ReflectionClass(AuthService::class);
        $reflectionHasError = $reflectionAuthService->getMethod('hasError');
        $reflectionHasError->setAccessible(true);

        $result = $reflectionHasError->invoke(new AuthService(), ['login' => $login, 'password' => $password]);

        $this->assertEquals($expected, $result);
    }

    public function dataForHasError()
    {
        return [
            ['login', 'password', false],
            ['', 'password', true],
            ['login', '', true],
            ['', '', true],
        ];
    }
    /**
     * @param $params
     * @param $expected
     * @return void
     * @dataProvider dataForTestAuth
     */
    public function testAuth($params, $expected)
    {
        $mockUser = $this->createMock(User::class);
        $mockUser->id = 1;
        $mockUser->login = $params['login'];
        $mockUser->password = password_hash($params['password'], PASSWORD_DEFAULT);
        $mockUser->role = 'role';

        $mockAuthService = $this->getMockBuilder(AuthService::class)
            ->onlyMethods(['getUserByLogin', 'sessionSet'])
            ->getMock();

        $mockAuthService->method('getUserByLogin')->willReturn($mockUser);

        $result = $mockAuthService->auth($params);
        $this->assertEquals($expected, $result);

    }

    public function dataForTestAuth()
    {
        return [
            [
                [
                    'login' => 'login',
                    'password' => 'password'
                ],
                [
                    'msg' => 'Успешно',
                    'success' => true
                ]
            ],
            [
                [
                    'login' => 'login',
                    'password' => ''
                ],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ],
            ],
            [
                [
                    'login' => '',
                    'password' => 'password'
                ],
                [
                    'msg' => 'Нет данных',
                    'success' => false,
                ],
            ],
        ];
    }
}