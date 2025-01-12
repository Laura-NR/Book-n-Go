<?php
use Symfony\Component\Yaml\Yaml;
/**
 * @file config.class.php
 * @class config
 * @brief Classe singleton pour la gestion de la configuration de l'application.
 *
 * La classe `config` est utilisée pour lire et stocker les paramètres de configuration
 * de l'application à partir d'un fichier YAML. Elle utilise le design pattern Singleton
 * pour s'assurer qu'une seule instance de la configuration est chargée et accessible
 * à travers l'application.
 *
 * La configuration est stockée sous forme de tableau associatif, accessible via les
 * méthodes de la classe.
 *
 * @note Cette classe utilise le composant Symfony Yaml pour le parsing du fichier YAML.
 */
class config{
    private static ?config $instance = null;

    private ?array $conf = null;

    private function __construct()
    {
        $this->conf = yaml::parseFile('./config/config.yaml');
        // parser le fichier YAML avec symfony/yaml
    }

    //Empecher le clonage de l'instance
    private function __clone()
    {

    }

    //Empecher la deserialisation de l'instance

    /**
     * @brief __wakeup
     * @return mixed
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Un singleton ne doit pas être déserialisé.");
    }

    /**
     * @brief recuperer la configuration
     * @return array|null
     */
    public function getConf(): ?array
    {
        return $this->conf;
    }

    /**
     * @brief récupérer l'objet config existant ou instancier le singleton si non-instancié
     * @return config
     */
    public static function getInstance(): config
    {
        if (is_null(self::$instance)) {
            self::$instance = new config();
        }
        return self::$instance;
    }
}
