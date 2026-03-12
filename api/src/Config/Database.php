<?php
namespace Src\Config;

use MongoDB\Client;
use MongoDB\Collection;
use Exception;

class Database
{
    private static ?Database $instance = null;
    private Client $client;
    private \MongoDB\Database $database;

    private function __construct()
    {
        try {
            // Kết nối MongoDB
            $this->client = new Client("mongodb://traininguser:trainingpassword@mongodb:27017/training");
            $this->database = $this->client->selectDatabase("training");
        } catch (Exception $e) {
            die("Kết nối MongoDB thất bại: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): ?Database
    {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getCollection($collectionName): Collection
    {
        return $this->database->selectCollection($collectionName);
    }
}