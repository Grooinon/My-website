<?php

namespace App\Helpers;

class View
{
    public static function render(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $viewsBaseDir = dirname(__DIR__, 2) . '/resources/views/';
        $viewFile = $viewsBaseDir . ltrim($viewPath, '/') . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View file [{$viewPath}] not found.";
            return;
        }

        // Extract variables to local view scope
        extract($data);

        // Flash messages and common user data
        $currentUser = Auth::user();
        $flashMessages = Flash::get();

        if ($layout !== null) {
            $layoutFile = $viewsBaseDir . ltrim($layout, '/') . '.php';
            if (!file_exists($layoutFile)) {
                http_response_code(500);
                echo "Layout file [{$layout}] not found.";
                return;
            }

            // Capture view content
            ob_start();
            require $viewFile;
            $content = ob_get_clean();

            // Render layout with $content
            require $layoutFile;
        } else {
            require $viewFile;
        }
    }
}
