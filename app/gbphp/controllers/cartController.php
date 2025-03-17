<?php

namespace App\controllers;

use App\main\App;
use App\services\renders\IRender;
use App\services\Request;
use function PHPUnit\Framework\throwException;

class cartController extends Controller
{
    protected $defaultAction = 'all';
    protected $countryRepository;

    public function __construct(IRender $render, Request $request)
    {
        parent::__construct($render, $request);
        $this->countryRepository = App::call()->CountryRepository;
    }

    public function allAction()
    {
        return $this->render('cart', [
            'countries' => $this->countryRepository->getAll(),
        ]);
    }
}