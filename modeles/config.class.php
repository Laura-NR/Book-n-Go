<?php
use Symfony\Component\Yaml\Yaml;
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
    public function __wakeup()
    {
        throw new Exception("Un singleton ne doit pas être déserialisé.");
    }

    /**
     * @return array|null
     */
    public function getConf(): ?array
    {
        return $this->conf;
    }

    public static function getInstance(): config
    {
        if (is_null(self::$instance)) {
            self::$instance = new config();
        }
        return self::$instance;
    }
}
