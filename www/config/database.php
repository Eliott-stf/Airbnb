<?php

//Valeur par defaut pour le developpement (seront remplacées par le .env si présent)
$defaults = [
    "DB_HOST" => "mariadb_airbnb",
    "DB_PORT" => "3306",
    "DB_NAME" => "airbnb",
    "DB_USER" => "admin",
    "DB_PASS" => "admin"
];

//fonction helper pour récupérer une variable d'environnement avec valeur par défaut 
$getEnv = function(string $key, string $default = ' ') use ($defaults): string{
    $value = getenv($key);
    if($value == false || $value === ''){
        return $defaults[$key] ?? $default;
    }
    return $value;
};

return [
    "driver" => "mysql",
    'host' => $getEnv("DB_HOST"),
    'port' => $getEnv("DB_PORT"),
    'dbname' => $getEnv("DB_NAME"),
    'user' => $getEnv("DB_USER"),
    'password' => $getEnv("DB_PASS"),
];
