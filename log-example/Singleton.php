<?php
require_once __DIR__ . '/LoggerFactory.php'; 

class Singleton 
{
    private static ?array $map = null;
    private static ?Singleton $instance = null;

    public static function getInstance(): Singleton 
    {
        $logger = LoggerFactory::getLogger(__FILE__);
        if (self::$instance === null) {
            $logger->info("Initializing instance............");
            self::$instance = new self();
            $logger->info("Instance initialized.");
        }
        return self::$instance;
    }

    // 2. DISABLE CLONING: Prevents copying the instance
    private function __clone() {}

    // 3. DISABLE UNSERIALIZATION: Prevents recreating the object via unserialize()
    public function __wakeup() {}


    private function __construct() {
         $logger = LoggerFactory::getLogger(__FILE__);
         if (self::$map != null) {
            $logger->info("Map already initialied.");
            return self::$map;
        } else {
            $logger->info("Initializing map...");
            $map = [];
            $map["key1"] = "value1";
            $map["key2"] = "value2";

            self::$map = $map;

            $logger->info("Total propeties count: " . sizeof(self::$map));
            
        }
    }
    
    public function getPproperty(string $key) : string
    {
        return self::$map[$key];
    }
}

