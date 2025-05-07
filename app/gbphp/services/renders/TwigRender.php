<?php

namespace App\Services\renders;

use App\main\App;
use App\Services\webpack\ManifestService;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * Класс рендера шаблонов Twig
 */
class TwigRender implements IRender
{
    /**
     * Экземпляр Twig
     * @var Environment Twig
     */
    protected Environment $twig;
    /**
     * @var ManifestService
     * Сервис для работы с манифестом
     */
    protected ManifestService $manifestService;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 2);

        $loader = new FilesystemLoader([
            $projectRoot . '/views/layouts',
            $projectRoot . '/views/',
        ]);

        $isDevMode = (getenv('APP_ENV') ?: 'production') === 'development';
        $this->twig = new Environment($loader, [
            'debug' => $isDevMode,
            'auto_reload' => $isDevMode,
        ]);
        if ($isDevMode) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        }

        $this->manifestService = App::call()->ManifestService;
        $this->initGlobal();
    }

    /**
     * Метод рендера шаблона
     * @param $template - имя шаблона
     * @param $params - параметры для шаблона
     * @return string
     */
    public function render($template, $params = []) : string
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

    /**
     * Инициализация глобальных переменных
     */
    protected function initGlobal(): void
    {
        $this->addUserToGlobal();
        $this->addGoodCategoriesToGlobal();
        $this->addGoodDesignersToGlobal();
        $this->addGoodBrandsToGlobal();
        $this->addCountriesToGlobal();
        $this->addAssetFunction();
    }

    /**
     * Добавление функции asset в Twig
     * @return void
     */
    protected function addAssetFunction(): void
    {
        $assetFunction = new TwigFunction('asset', [$this->manifestService, 'getAssetPath']);

        $this->twig->addFunction($assetFunction);
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