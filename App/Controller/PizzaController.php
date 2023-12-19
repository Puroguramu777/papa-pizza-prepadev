<?php 

namespace App\Controller;

use App\Model\User;
use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class PizzaController extends Controller
{
    public function home()
    {
        //on instancie la class View et on lui passe en paramètre le chemin de la vue
        // dossier/fichier
        $view = new View('home/index');

        //on appelle la méthode render() de la class View
        $view->render();
    }

    //méthode qui récupère la liste des pizzas
    public function getPizzas()
    {
        $view_data = [
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
        ];

        $view = new View('home/pizzas');

        $view->render($view_data);
    }

    //méthode qui récupère une pizza par son id
    public function getPizzaById(int $id)
    {
        $view_data = [
            'pizza' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaById($id),
        ];

        $view = new View('home/pizza_detail');

        $view->render($view_data);
    }

    // View pour le formulaire de création de pizzas personalisées
    public function addPizzaPerso()
    {
        $view_data = [
            'form_result' => Session::get(Session::FORM_RESULT)
        ];


        $view = new View('user/pizza_perso');

        $view->render($view_data);
    }

    // View pour voir les pizzas créée par l'utilisateur (pizza personalisées)
    public function viewPizzaPerso($id)
    {
        
        $view_data = [
            
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getUsersPizzas($id)
        ];

        $view = new View('user/mes_pizzas');

        $view->render($view_data);
    }


    // Création de la view de chaque update du user
    public function UpdateUserLastname(int $id)
    {
        //on récupère les données de l'utilisateur
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];
        
        $view = new View('user/updateUserLastname');

        $view->render($view_data);
    }

    public function UpdateUserFirstname(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];
        
        $view = new View('user/updateUserFirstname');

        $view->render($view_data);
    }

    public function UpdateUserEmail(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];
        
        $view = new View('user/updateUserEmail');

        $view->render($view_data);
    }

    public function UpdateUserPhone(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];
        
        $view = new View('user/updateUserPhone');

        $view->render($view_data);
    }

    public function updatePizzaView(int $id)
    {
        $view_data = [
            'pizza' => AppRepoManager::getRm()->getPizzaRepository()->getPizzaById($id),
        ];

        $view = new View('admin/pizza-update');

        $view->render($view_data);
    }

    


        
    

    
    

    

    

    
    

    

    


    

    

    
}