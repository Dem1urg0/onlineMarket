<?php

namespace App\services\webpack;

class ManifestService
{
    public function getManifest()
    {
        $manifestPath = dirname(dirname(__DIR__)) . '/public/dist/manifest.json';
        if (!file_exists($manifestPath)) {
            throw new \Exception("Manifest file not found: " . $manifestPath);
        }
        $manifestContent = file_get_contents($manifestPath);
        return json_decode($manifestContent, true);
    }
}