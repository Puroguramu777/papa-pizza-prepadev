<?php

namespace App;

use App\Model\User;
use MiladRahimi\PhpRouter\Router;
use App\Controller\AuthController;
use App\Controller\UserController;
use App\Controller\AdminController;
use App\Controller\PizzaController;
use Core\Database\DatabaseConfigInterface;
use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use MiladRahimi\PhpRouter\Exceptions\InvalidCallableException;

class App implements DatabaseConfigInterface
{
    //on déclare des constantes pour la connexion à la base de données
    private const DB_HOST = 'database';
    private const DB_NAME = 'papapizza';
    private const DB_USER = 'admin';
    private const DB_PASS = 'admin';

    //on déclare une propriété privée qui va contenir une intance de app
    //Design pattern Singleton
    private static ?self $instance = null;

    //méthode public appelé au démarrage de l'application dans index.php
    public static function getApp():self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //On va configurer notre router
    private Router $router;

    public function getRouter(): Router
    {
        return $this->router;
    }

    private function __construct()
    {
        $this->router = Router::create();
    }

    //on aura 3 méthodes à définir pour le router
    //1: méthode start() qui va démarrer le router dans l'application
    public function start(): void
    {
        //on ouvre l'accès à la session
        session_start();
        //on enregistre les routes
        $this->registerRoutes();
        //on démarre le router
        $this->startRouter();
    }

    //2: méthode qui enregistre les routes
    private function registerRoutes()
    {
        //exemple de routes avec un controller
        // $this->router->get('/', [ToyController::class, 'index']);
        $this->router->get('/', [PizzaController::class, 'home']);
        $this->router->get('/pizzas', [PizzaController::class, 'getPizzas']);
        $this->router->get('/pizza/{id}', [PizzaController::class, 'getPizzaById']);
        $this->router->get('/connexion', [AuthController::class, 'loginForm']);
        $this->router->post('/login', [AuthController::class, 'login']);
        $this->router->get('/inscription', [AuthController::class, 'registerForm']);
        $this->router->post('/register', [AuthController::class, 'register']);
        $this->router->get('/account/{id}', [UserController::class, 'account']);
        $this->router->get('/admin/home', [AdminController::class, 'account']);
        $this->router->get('/logout', [AuthController::class, 'logout']);

        $this->router->get('/admin/user/list', [AdminController::class, 'listUser']);
        $this->router->get('/admin/team/list', [AdminController::class, 'listTeam']);
        $this->router->get('/admin/pizza/list', [AdminController::class, 'listPizza']);
        $this->router->get('/admin/order/list', [AdminController::class, 'listOrder']);
        
        $this->router->get('/admin/user/delete/{id}', [AdminController::class, 'deleteUser']);
        $this->router->get('/admin/team/add', [AdminController::class, 'addTeam']);
        $this->router->post('/register-team', [AuthController::class, 'registerTeam']);

        $this->router->get('/admin/pizza/add', [AdminController::class, 'addPizza']);
        $this->router->post('/add-pizza-form', [AdminController::class, 'addPizzaForm']);

        $this->router->get('/pizzas/personaliser', [PizzaController::class, 'addPizzaPerso']);

        

    }

    //3: méthode qui va démarrer le router
    private function startRouter()
    {
        try {
            $this->router->dispatch();
        } catch (RouteNotFoundException $e) {
            //TODO : gérer la vue 404
            var_dump('Erreur 404: ' . $e);
        } catch (InvalidCallableException $e) {
            //TODO : gérer la vue 500
            var_dump('Erreur 500: ' . $e);
        }
    }

    //on déclare nos méthodes imposé par l'interface DatabaseConfigInterface
    public function getHost(): string
    {
        return self::DB_HOST;
    }

    public function getName(): string
    {
        return self::DB_NAME;
    }

    public function getUser(): string
    {
        return self::DB_USER;
    }

    public function getPass(): string
    {
        return self::DB_PASS;
    }
}
