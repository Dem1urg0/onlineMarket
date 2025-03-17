<?php
return [
    'name' => 'Мой магазин',
    'defaultController' => 'user',

    'components' => [
        'db' => [
            'class' => \App\services\db::class,
            'config' => [
                'driver' => 'mysql',
                'host' => 'mariadb',
                'db' => 'dbphp',
                'charset' => 'UTF8',
                'username' => 'root',
                'password' => 'rootroot',
            ],
        ],
        //Validators
        'Validator' => [
            'class' => \App\validators\Validator::class
        ],
        'GoodValidator' => [
            'class' => \App\validators\GoodValidator::class
        ],
        'UserValidator' => [
            'class' => \App\validators\UserValidator::class
        ],
        'OrderValidator' => [
            'class' => \App\validators\OrderValidator::class
        ],
        'PaginationValidator' => [
            'class' => \App\validators\PaginationValidator::class
        ],
        'CountryValidator' => [
            'class' => \App\validators\CountryValidator::class
        ],
        //Services
        'render' => [
            'class' => \App\services\renders\TwigRender::class,
        ],
        'Response' => [
            'class' => \App\services\Response::class
        ],
        'Request' => [
            'class' => \App\services\Request::class,
        ],
        'AuthService' => [
            'class' => \App\Services\AuthService::class
        ],
        //Middlewares
        'RoleMiddleware' => [
            'class' => \App\Middleware\RoleMiddleware::class
        ],
        'AuthMiddleware' => [
            'class' => \App\Middleware\AuthMiddleware::class
        ],
        //Country
        'Country' => [
            'class' => \App\Entities\Country::class
        ],
        'CountryRepository' => [
            'class' => \App\Repositories\CountryRepository::class
        ],
        //Code
        'Code' => [
            'class' => \App\Entities\Code::class
        ],
        'CodeRepository' => [
            'class' => \App\Repositories\CodeRepository::class
        ],
        'CodeService' => [
            'class' => \App\Services\CodeService::class
        ],
        'CodeValidator' => [
            'class' => \App\validators\CodeValidator::class
        ],
        //API
        'apiService' => [
            'class' => \App\services\api\apiService::class
        ],
        'apiUserService' => [
            'class' => \App\services\api\apiUserService::class
        ],
        'apiAdminService' => [
            'class' => \App\services\api\apiAdminService::class
        ],
        //User
        'User' => [
            'class' => \App\Entities\User::class,
        ],
        'UserRepository' => [
            'class' => \App\repositories\UserRepository::class,
        ],
        'UserService' => [
            'class' => \App\services\UserService::class,
        ],
        //OrderEntity
        'Order' => [
            'class' => \App\Entities\Order\Order::class
        ],
        'OrderItem' => [
            'class' => \App\Entities\Order\OrderItem::class
        ],
        'OrderBilling' => [
            'class' => \App\Entities\Order\OrderBilling::class
        ],
        'OrderAddress' => [
            'class' => \App\Entities\Order\OrderAddress::class
        ],
        'OrderInfo' => [
            'class' => \App\Entities\Order\OrderInfo::class
        ],
        //OrderRepository
        'OrderRepository' => [
            'class' => \App\Repositories\Order\OrderRepository::class
        ],
        'OrderItemRepository' => [
            'class' => \App\Repositories\Order\OrderItemRepository::class
        ],
        'OrderBillingRepository' => [
            'class' => \App\Repositories\Order\OrderBillingRepository::class
        ],
        'OrderAddressRepository' => [
            'class' => \App\Repositories\Order\OrderAddressRepository::class
        ],
        'OrderInfoRepository' => [
            'class' => \App\Repositories\Order\OrderInfoRepository::class
        ],
        //OrderService
        'OrderService' => [
            'class' => \App\Services\OrderService::class
        ],
        //GoodEntity
        'Good' => [
            'class' => \App\Entities\Good\Good::class,
        ],
        'GoodCategory' => [
            'class' => \App\Entities\Good\GoodCategory::class,
        ],
        'GoodBrand' => [
            'class' => \App\Entities\Good\Goodbrand::class,
        ],
        'GoodDesigner' => [
            'class' => \App\Entities\Good\GoodDesigner::class,
        ],
        'GoodSize' => [
            'class' => \App\Entities\Good\GoodSize::class,
        ],
        'Storage' => [
            'class' => \App\Entities\Good\Storage::class
        ],
        //GoodRepository
        'GoodRepository' => [
            'class' => \App\Repositories\Good\GoodRepository::class,
        ],
        'GoodBrandRepository' => [
            'class' => \App\Repositories\Good\GoodBrandRepository::class
        ],
        'GoodDesignerRepository' => [
            'class' => \App\Repositories\Good\GoodDesignerRepository::class
        ],
        'GoodCategoryRepository' => [
            'class' => \App\Repositories\Good\GoodCategoryRepository::class
        ],
        'GoodSizeRepository' => [
            'class' => \App\Repositories\Good\GoodSizeRepository::class
        ],
        'StorageRepository' => [
            'class' => \App\Repositories\Good\StorageRepository::class
        ],
        //GoodService
        'GoodService' => [
            'class' => \App\Services\GoodService::class
        ],
    ],
];
