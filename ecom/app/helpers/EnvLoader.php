<?php

class EnvLoader
{
    private static ?array $properties = null;
    private static ?EnvLoader $instance = null;

    public static function getInstance(): EnvLoader 
    {
        $logger = LoggerFactory::getLogger(__CLASS__);
        if (self::$instance === null) {
            $logger->info("Initializing instance............");
            self::$instance = new self();
            $logger->info("Instance initialized.");
        }
        return self::$instance;
    }


    private function __construct()
    {
        $logger = LoggerFactory::getLogger(__CLASS__);

        $filePath = __DIR__ . '/../../.env';

        try {
            if (!file_exists($filePath)) {
                throw new Exception("Environment file not found at: {$filePath}");
            }

            self::load($filePath);
        } catch (Exception $e) {
            $logger->error("Error loading file " . $e->getMessage());
        }
    }
    /**
     * Loads a .env file and returns its properties as an array.
     *
     * @param string $filePath Path to the env file
     * @return array Associative array of environment variables
     * @throws Exception If the file does not exist
     */
    public static function load(string $filePath): array
    {
        $logger = LoggerFactory::getLogger(__CLASS__);

        if (self::$properties != null) {
            return self::$properties;
        }

        self::$properties = [];

        // Read file into an array of lines, skipping newlines
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip lines that are empty or start with a comment
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // Split into key and value by the first '=' sign
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);

                $key = trim($key);
                $value = trim($value);

                // Strip surrounding quotes from the value if they exist
                $value = preg_replace('/^["\'](.*)["\']$/', '$1', $value);

                self::$properties[$key] = $value;
            }
        }
        $logger->info("Total properties found " . sizeof(self::$properties));

        return self::$properties;
    }

    public static function getProperty(string $key): string
    {   
        return self::$properties[$key];
    }
}
