<?php
class config{  #WIP
    private static ?config $instance = null;

    # LISTE DE CLES/VALUEURS DES INFOS DE LA BD
    # LISTE DE CLES/VALEURS DES INFOS DU SITE

    private function __construct()
    {
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
}
