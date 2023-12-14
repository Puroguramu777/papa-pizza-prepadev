<?php 

namespace App\Controller;

use Core\View\View;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;

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

    public function addCartPizza(int $id)
    {
        $form_result = new FormResult();

        $addCartPizza = AppRepoManager::getRm()->getPizzaRepository()->addCartPizza($id);

        if (!$addCartPizza) {
            $form_result->addError(new FormError('Une erreur est survenu lors de la suppression de la Pizza'));
        }

        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/pizza/list');
        }
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/pizza/list');
    }

    public function UpdateUser(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];
        
        $view = new View('user/updateUser');

        $view->render($view_data);
    }

    

    


    

    

    
}