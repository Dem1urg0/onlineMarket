<?php

namespace App\controllers;

class homeController extends Controller
{
    protected $defaultAction = 'index';

    public function indexAction(){
        return $this->render('home', []);
    }
}