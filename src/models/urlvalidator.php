<?php

class UrlValidator {
    private static array $blacklist = [];

    private static function loadBlacklist(): void {
        if (empty(self::$blacklist)) {
            $path = __DIR__ . "/../../config/blacklist.txt";
            if (file_exists($path)) {
                self::$blacklist = array_map("trim", file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
            }
        }
    }

    public static function isValid(string $url): bool {
        self::loadBlacklist();

        // Validar formato de URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // validar que use HTTPS
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (strtolower($scheme) !== 'https') {
            return false;
        }

        $urlLower = strtolower($url);

        // Revisar cada palabra o dominio en blacklist
        foreach (self::$blacklist as $badWord) {
            if ($badWord !== '' && stripos($urlLower, strtolower($badWord)) !== false) {
                return false;
            }
        }

        return true;
    }
}
