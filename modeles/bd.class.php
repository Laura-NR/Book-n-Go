<?php

class bd{
    private static ?bd $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $config = config::getInstance()->getConf();
        $dbConfig = $config['database'];
        try {
            $this->pdo = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname='.$dbConfig['name'].';charset=utf8mb4', $dbConfig['user'], $dbConfig['pass']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
            die();
        }
    }

    public static function getInstance(): bd
    {
        if (is_null(self::$instance)) {
            self::$instance = new bd();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }


    //Empecher le clonage de l'instance
    private function __clone()
    {

    }

    //Empecher la deserialisation de l'instance
    public function __wakeup()
    {
        throw new Exception("Un singleton ne doit pas être déserialisé.");
    }
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}