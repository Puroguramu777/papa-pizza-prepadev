<?php

namespace App\Controller;

use App\Model\User;
use Core\View\View;
use App\Model\Pizza;
use App\AppRepoManager;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class UserController extends Controller
{
    public function account(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id)
        ];

        $view = new View('user/account');

        $view->render($view_data);
    }

    public function addCustomPizzaForm(ServerRequest $request)
    {
        $post_data = $request->getParsedBody();

        $file_data = $_FILES['image_path'];
        $form_result = new FormResult();
        //on crée des variables
        $name = $post_data['name']; //nom de la pizza
        $user_id = $post_data['user_id']; //id de l'utilisateur
        $array_ingredients = $post_data['ingredients']; //tableau des ingredients

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
        //sinon on redirige vers la page des
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/pizza/list');
    }

    public function panier(int $id)
    {
        $view_data = [
            'user' => AppRepoManager::getRm()->getUserRepository()->findUserbyId($id),
            'pizzas' => AppRepoManager::getRm()->getPizzaRepository()->getAllPizzas()
        ];

        $view = new View('user/panier');

        $view->render($view_data);
    }



    public function UpdateUserLastnameForm(ServerRequest $request): array
    {

        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();


        if (
            empty($data_form['lastname'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner ce champ'));
        } else {
            $data_user = [
                'lastname' => trim($data_form['lastname']),
                'id' => Session::get('USER')->id
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->UpdateUserLastnameMethod($data_user);
        }


        self::redirect('/account/' . Session::get('USER')->id);
    }

    public function UpdateUserFirstnameForm(ServerRequest $request): array
    {

        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();


        if (
            empty($data_form['firstname'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner ce champ'));
        } else {
            $data_user = [
                'firstname' => trim($data_form['firstname']),
                'id' => Session::get('USER')->id
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->UpdateUserFirstnameMethod($data_user);
        }


        self::redirect('/account/' . Session::get('USER')->id);
    }

    public function UpdateUserEmailForm(ServerRequest $request): array
    {

        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();


        if (
            empty($data_form['email'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner ce champ'));
        } else {
            $data_user = [
                'email' => trim($data_form['email']),
                'id' => Session::get('USER')->id
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->UpdateUserEmailMethod($data_user);
        }


        self::redirect('/account/' . Session::get('USER')->id);
    }

    public function UpdateUserPhoneForm(ServerRequest $request): array
    {

        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();


        if (
            empty($data_form['phone'])
        ) {
            $form_result->addError(new FormError('Veuillez renseigner ce champ'));
        } else {
            $data_user = [
                'phone' => trim($data_form['phone']),
                'id' => Session::get('USER')->id
            ];

            $user = AppRepoManager::getRm()->getUserRepository()->UpdateUserPhoneMethod($data_user);
        }


        self::redirect('/account/' . Session::get('USER')->id);
    }
}
