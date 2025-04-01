<?php

namespace App\services\renders;

use App\main\App;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * Класс рендера шаблонов Twig
 */
class TwigRender implements IRender
{
    /**
     * Экземпляр Twig
     * @var Environment Twig
     */
    protected $twig;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $loader = new FilesystemLoader([
            dirname(dirname(__DIR__)) . '/views/layouts',
            dirname(dirname(__DIR__)) . '/views/',
        ]);
        $this->twig = new Environment($loader);

        $this->initGlobal();
    }

    /**
     * Метод рендера шаблона
     * @param $template - имя шаблона
     * @param $params - параметры для шаблона
     * @return string
     */
    public function render($template, $params = [])
    {
        $template .= '.twig';
        try {
            return $this->twig->render($template, $params);
        } catch (LoaderError $e) {
            // Handle template not found
            error_log("Template loading error: " . $e->getMessage());
            return "Error: Template '$template' not found.";
        } catch (RuntimeError $e) {
            // Handle runtime errors
            error_log("Template runtime error: " . $e->getMessage());
            return "Error rendering template: " . $e->getMessage();
        } catch (SyntaxError $e) {
            // Handle syntax errors in template
            error_log("Template syntax error: " . $e->getMessage());
            return "Error in template syntax: " . $e->getMessage();
        }
    }

    protected function addAssetPathsToGlobal()
    {
        $environment = getenv('APP_ENV') ?: 'production';
        $assets = [];
        if ($environment === 'development') {
            $devServerBaseUrl = 'http://localhost:8081/dist/';
            $assets['index.js'] = $devServerBaseUrl . 'index.js';
            $assets['admin.js'] = $devServerBaseUrl . 'admin.js';
            $assets['index.css'] = $devServerBaseUrl . 'index.css';
        } else {
            try {
                $manifest = App::call()->ManifestService->getManifest();
                $assets = $manifest;
            } catch (\Exception $e) {
                error_log("Asset Manifest Error: " . $e->getMessage());
                $assets['index.js'] = '';
                $assets['admin.js'] = '';
                $assets['index.css'] = '';
            }
        }
        $this->twig->addGlobal('assets', $assets);
    }

    /**
     * Инициализация глобальных переменных
     */
    protected function initGlobal()
    {
        $this->addUserToGlobal();
        $this->addGoodCategoriesToGlobal();
        $this->addGoodDesignersToGlobal();
        $this->addGoodBrandsToGlobal();
        $this->addCountriesToGlobal();
        $this->addAssetPathsToGlobal();
    }

    /**
     * Добавление текущего пользователя в глобальные переменные
     */
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

    /**
     * Добавление категорий товаров в глобальные переменные
     */
    protected function addGoodCategoriesToGlobal()
    {
        $categories = App::call()->GoodCategoryRepository->getAll();

        $this->twig->addGlobal('goodsCategories', $categories);
    }

    /**
     * Добавление дизайнеров товаров в глобальные переменные
     */
    protected function addGoodDesignersToGlobal()
    {
        $designers = App::call()->GoodDesignerRepository->getAll();

        $this->twig->addGlobal('goodsDesigners', $designers);
    }

    /**
     * Добавление брендов товаров в глобальные переменные
     */
    protected function addGoodBrandsToGlobal()
    {
        $brands = App::call()->GoodBrandRepository->getAll();

        $this->twig->addGlobal('goodsBrands', $brands);
    }

    /**
     * Добавление стран в глобальные переменные
     */
    protected function addCountriesToGlobal()
    {
        $countries = App::call()->CountryRepository->getAll();

        $this->twig->addGlobal('countries', $countries);
    }
}