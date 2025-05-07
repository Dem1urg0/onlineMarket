<?php

namespace App\Services\webpack;

class ManifestService
{
    /**
     * @var ManifestService|null
     * Кеш в памяти для манифеста
     */
    private ?array $manifestData = null;
    /**
     * @var string
     * Путь к манифесту
     */
    private string $manifestPath;
    /**
     * @var bool
     * Режим разработки или продакшн
     */
    private bool $isDevMode;
    /**
     * @var string
     * URL для Dev Server
     */
    private string $devServerBaseUrl = 'http://localhost:8081/dist/'; // Настройка Dev Server URL

    /**
     * Конструктор класса.
     * Определяет путь к манифесту и режим работы (dev или prod)
     */
    public function __construct()
    {
        $projectRoot = dirname(__DIR__, 2);
        $this->manifestPath = $projectRoot . '/public/dist/manifest.json';

        $this->isDevMode = (getenv('APP_ENV') ?: 'production') === 'development';
    }

    /**
     * Загружает манифест из файла с кешированием в памяти.
     * Возвращает null, если файл не найден или ошибка декодирования.
     * @return array|null
     */
    private function loadManifest(): ?array
    {
        if ($this->manifestData !== null) {
            return ($this->manifestData === ['error']) ? null : $this->manifestData;
        }

        if (!file_exists($this->manifestPath)) {
            error_log("Manifest file not found: " . $this->manifestPath);
            $this->manifestData = ['error'];
            return null;
        }

        $manifestContent = file_get_contents($this->manifestPath);
        $decodedData = json_decode($manifestContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error decoding manifest file '{$this->manifestPath}': " . json_last_error_msg());
            $this->manifestData = ['error'];
            return null;
        }

        $this->manifestData = $decodedData;
        return $this->manifestData;
    }

    /**
     * Получает публичный URL для ассета по его ключу из манифеста.
     * Обрабатывает режимы development и production.
     *
     * @param string $key Ключ ассета (например, 'index.js', 'promo.jpg')
     * @return string Финальный URL ассета
     */
    public function getAssetPath(string $key): string
    {
        //Dev
        if ($this->isDevMode) {
            return $this->devServerBaseUrl . ltrim($key, '/');
        }

        // Production
        $manifest = $this->loadManifest();

        if ($manifest !== null && isset($manifest[$key])) {
            return $manifest[$key];
        }

        error_log("Asset key '{$key}' not found in production manifest: {$this->manifestPath}");

        return '/dist/' . $key;
    }
}