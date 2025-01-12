<?php

/**
 * @file bd.class.php
 *
 * Cette classe permet de fournir une connexion à la base de données en utilisant le design pattern Singleton.
 *
 * @class bd
 * @brief Singleton de connexion à la base de données
 */
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

    /**
     * Retourne l'instance unique de la classe bd.
     *
     * La méthode getInstance est la seule manière d'accéder à l'instance
     * de la classe bd. Si l'instance n'existe pas encore, elle est créée
     * à l'aide du constructeur privé.
     *
     * @return bd L'instance unique de la classe bd.
     */
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