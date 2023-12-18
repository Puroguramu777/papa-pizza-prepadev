<?php

namespace App\Controller;

use App\AppRepoManager;
use Core\View\View;
use Core\Controller\Controller;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Laminas\Diactoros\ServerRequest;

class AdminController extends Controller
{


    public function account()
    {

        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $view = new View('admin/admin');

        $view->render();
    }

    public function listUser()
    {
        $users = AppRepoManager::getRm()->getUserRepository()->getAllClientActif();
        $data_view = [
            'users' => $users,
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');

        $view = new View('admin/list-user');

        $view->render($data_view);
    }

    public function deleteUser(int $id)
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $form_result = new FormResult();

        $deleteUser = AppRepoManager::getRm()->getUserRepository()->deleteUser($id);

        if (!$deleteUser) {
            $form_result->addError(new FormError('Une erreur est survenu lors de la suppression de l\'utilisateur'));
        }

        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/user/list');
        }
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/user/list');
    }

    public function addTeam()
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $view = new View('admin/add-team');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }


    public function listTeam()
    {
        $users = AppRepoManager::getRm()->getUserRepository()->getAllAdminActif();
        $data_view = [
            'users' => $users,
            'form_result' => Session::get(Session::FORM_RESULT)
        ];


        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $view = new View('admin/list-team');

        $view->render($data_view);
    }


    public function listPizza()
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $view_data = [
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzasWithInfo(),
            'form_result' => Session::get(Session::FORM_RESULT)

        ];

        $view = new View('admin/list-pizza');

        $view->render($view_data);
    }


    public function addPizza()
    {

        $view_data = [
            'form_result' => Session::get(Session::FORM_RESULT)
        ];


        $view = new View('admin/add-pizza');

        $view->render($view_data);
    }

    public function addPizzaForm(ServerRequest $request)
    {
        $post_data = $request->getParsedBody();
        
        $file_data = $_FILES['image_path'];
        $form_result = new FormResult();
        //on crée des variables
        $name = $post_data['name']; //nom de la pizza
        $user_id = $post_data['user_id']; //id de l'utilisateur
        $array_ingredients = $post_data['ingredients']; //tableau des ingredients
        $array_size = $post_data['size_id']; //tableau des tailles
        $array_price = $post_data['price']; //tableau des prix
        $image_name = $file_data['name']; //nom de l'image
        $tmp_path = $file_data['tmp_name']; //chemin temporaire de l'image
        $public_path = 'public/assets/images/pizza/'; //chemin public de l'image
        $form_result = new FormResult();

        //condition pour restreindre les types de fichiers que l'on souhaite recevoir
        if (
            $file_data['type'] !== 'image/jpeg' &&
            $file_data['type'] !== 'image/png' &&
            $file_data['type'] !== 'image/jpg' &&
            $file_data['type'] !== 'image/webp'
        ) {
            $form_result->addError(new FormError('Le format de l\'image n\'est pas valide'));
        } else if (
            //on vérifie que les autres champs sont remplis
            empty($name) ||
            empty($user_id) ||
            empty($array_ingredients) ||
            empty($array_size) ||
            empty($array_price) ||
            empty($image_name)
        ) {
            $form_result->addError(new FormError('Veuillez remplir tous les champs'));
        } else {

            //on redefinit un nom unique pour l'image
            $filename = uniqid() . '_' . $image_name;
            $slug = explode('.', strtolower(str_replace(' ', '-', $filename)))[0];
            //le chemin de destination
            $imgPathPublic = PATH_ROOT . $public_path . $filename;
            //on reconstruit un tableau de données
            $data_pizza = [
                'name' => htmlspecialchars(trim($name)),
                'image_path' => htmlspecialchars(trim($filename)),
                'user_id' => intval($user_id),
                'is_active' => 1,
            ];

            //on va déplacer le fichier tmp dans son dossier de destination dans une condition
            if (move_uploaded_file($tmp_path, $imgPathPublic)) {
                // appel du repository pour inserer dans la bdd
                $pizza = AppRepoManager::getRm()->getPizzaRepository()->insertPizza($data_pizza);
                //on vérifie que la pizza a bien été insérée
                if (!$pizza) {
                    $form_result->addError(new FormError('Erreur lors de l\'insertion de la pizza'));
                } else {
                    //on récupère l'id de la pizza
                    $pizza_id = $pizza->id;
                    //on va insérer les ingrédients de la pizza
                    foreach ($array_ingredients as $ingredient_id) {
                        //on crée un tableau de données
                        $data_pizza_ingredient = [
                            'pizza_id' => intval($pizza_id),
                            'ingredient_id' => intval($ingredient_id),
                            'quantity' => 1,
                            'unit_id' => 5
                        ];
                        //on appelle la méthode qui va insérer les ingrédients de la pizza
                        $pizza_ingredient = AppRepoManager::getRm()->getPizzaIngredientRepository()->insertPizzaIngredient($data_pizza_ingredient);
                        //on vérifie que l'insertion s'est bien passée
                        if (!$pizza_ingredient) {
                            $form_result->addError(new FormError('Erreur lors de l\'insertion des ingrédients de la pizza'));
                        }
                    }
                    //on va insérer les tailles de la pizza
                    foreach ($array_size as $size_id) {
                        //on crée un tableau de données
                        $data_price = [
                            'pizza_id' => intval($pizza_id),
                            'size_id' => intval($size_id),
                            'price' => floatval($array_price[$size_id - 1])
                        ];
                        //on appelle la méthode qui va insérer les tailles de la pizza
                        $price = AppRepoManager::getRm()->getPriceRepository()->insertPrice($data_price);
                        //on vérifie que l'insertion s'est bien passée
                        if (!$price) {
                            $form_result->addError(new FormError('Erreur lors de l\'insertion des tailles de la pizza'));
                        }
                    }
                }
            } else {
                $form_result->addError(new FormError('Erreur lors de l\'upload de l\'image'));
            }
        }
        //si il y a des erreurs
        if ($form_result->hasErrors()) {
            //on stocke les erreurs dans la session
            Session::set(Session::FORM_RESULT, $form_result);
            //on redirige vers la page d'ajout de jouet
            self::redirect('/admin/pizza/add');
        }
        //sinon on redirige vers la page admin
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/pizza/list');
    }

    public function deletePizza(int $id)
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $form_result = new FormResult();

        $deletePizza = AppRepoManager::getRm()->getPizzaRepository()->deletePizza($id);

        if (!$deletePizza) {
            $form_result->addError(new FormError('Une erreur est survenu lors de la suppression de la Pizza'));
        }

        if ($form_result->hasErrors()) {
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/pizza/list');
        }
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/pizza/list');
    }

    public function updatePizza(int $id)
    {
        
    }





    public function listOrder()
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $view = new View('admin/list-order');

        $view->render();
    }
}
