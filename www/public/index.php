<?php
/**
 * ===========================
 * CAHRGEMENT DE L'APPLICATION
 * ===========================
 */

use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\HoteController;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Application;
use App\Controller\BookingController;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Core\Middleware\CsrfMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * ==============================
 * CHARGEMENT DE LA CONFIGURATION
 * ==============================
 */
$dbConfig = require_once __DIR__ . '/../config/database.php';

/**
 * =========================
 * INITIALISER L'APPLICATION
 * =========================
 * Créer l'instance unique de l'applciation (Singlton Pattern)
 * Une seule instance de l'application esxiste dans toute l'execution
 */
$app= Application::create(__DIR__ . '/../');

//Définir les chemins des vues (templates)
//CONCEPT: Configuration des chemins pour le moteur de templates
$app->setViewsPath(__DIR__ . '/../views');
$app->setPartialsPath(__DIR__ . '/../views/_templates');

/**
 * ========================================
 * CHARGEMENT DES VARIABLES D'ENVIRONNEMENT
 * ========================================
 * Charger les variables depuis le fichier .env 
 */
if (file_exists(__DIR__ . '/../.env')){
    $app->loadEnv();
}

/**
 * CONFIGURATION DU MODE DEBUG
 */
$debug = getenv('APP_DEBUG') === 'true' || getenv("APP_DEBUG") == "1";
if(!defined("APP_DEBUG")){
    define("APP_DEBUG", $debug);
}

$app->getConfig()->set('app.debug' , $debug);
error_reporting($debug ? E_ALL : 0);
ini_set ("display_errors", $debug ? "1" : "0");

/**
 * CONFIG DU CONTENAIRE DI 
 */
$container = $app->getContainer();
$container->singleton(EntityManager::class, function() use ($dbConfig){
    return new EntityManager($dbConfig);
});


/**
 * Enregistrer le AutManager 
 */
$container->singleton(AuthManager::class, function() use ($container){
    $em = $container->make(EntityManager::class);
    return new AuthManager([
        "user_class" => \App\Entity\User::class,
        "entity_manager" => $em
    ]);
});

/**
 * =======================================
 * CONFIGURATION DU ROUTEUR ET MIDLLEWARES
 * =======================================
*/
$router =$app->getRouter();
$router->addMiddleware(new CsrfMiddleware());

//TODO: ici les futurs middleware


/**
 * =========================
 * ENREGISTREMENT DES ROUTES
 * =========================
 */
$router->registerRoutes(HomeController::class);
$router->registerRoutes(AuthController::class);
$router->registerRoutes(HoteController::class);
$router->registerRoutes(BookingController::class);



//Démarer l'application
$app->start();
$app->handle();