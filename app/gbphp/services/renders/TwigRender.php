<?php

namespace App\services\renders;

use App\main\App;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigRender implements IRender
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader([
            dirname(dirname(__DIR__)) . '/views/layouts',
            dirname(dirname(__DIR__)) . '/views/',
        ]);
        $this->twig = new Environment($loader);

        $this->initGlobal();
    }

    public function render($template, $params = [])
    {
        $template .= '.twig';
        try {
            return $this->twig->render($template, $params);
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }
    }

    protected function initGlobal()
    {
        $this->addUserToGlobal();
        $this->addGoodCategoriesToGlobal();
        $this->addGoodDesignersToGlobal();
        $this->addGoodBrandsToGlobal();
    }

    protected function addUserToGlobal()
    {
        $currentUser = App::call()->Request->sessionGet('user');
        if (empty($currentUser)) {
            $currentUser = [
                'id' => 0,
            ];
        }
        $this->twig->addGlobal('currentUser', $currentUser);
    }

    protected function addGoodCategoriesToGlobal()
    {
        $categories = App::call()->GoodCategoryRepository->getAll();

        $this->twig->addGlobal('goodsCategories', $categories);
    }

    protected function addGoodDesignersToGlobal()
    {
        $designers = App::call()->GoodDesignerRepository->getAll();

        $this->twig->addGlobal('goodsDesigners', $designers);
    }

    protected function addGoodBrandsToGlobal()
    {
        $brands = App::call()->GoodBrandRepository->getAll();

        $this->twig->addGlobal('goodsBrands', $brands);
    }
}