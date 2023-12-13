<?php

namespace App\Controller;

use App\AppRepoManager;
use App\Model\User;
use Core\View\View;
use Core\Form\FormError;
use Core\Form\FormResult;
use Core\Session\Session;
use Core\Controller\Controller;
use Laminas\Diactoros\ServerRequest;

class AuthController extends Controller
{
    public function validEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public function validPassword(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})/', $password);
    }

    public function userExist(string $email): bool
    {
        $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($email);
        return !is_null($user);
    }

    public function validInput(string $value)
    {
        $value = trim($value);
        $value = strip_tags($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);

        return $value;
    }

    //méthode qui renvoi sur la vue du formulaire de connexion
    public function loginForm()
    {
        $view = new View('auth/login');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }
    //méthode qui receptionne les données du formulaire de connexion
    public function login(ServerRequest $request)
    {
        //on récupère les données du formulaire sous forme de tableau associatif
        $post_data = $request->getParsedBody();
        //on instancie notre class FormResult (elle s'occupe de stocker les messages d'erreur dans la session)
        $form_result = new FormResult();
        //on instancie un User
        $user = new User();

        //on vérifie que les champs soient remplis
        if(empty( $post_data['email']) || empty( $post_data['password'])){
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        }else{
            $email = strtolower($this->validInput($post_data['email']));
            //on va vérifier que l'email existe en bdd
            $user = AppRepoManager::getRm()->getUserRepository()->findUserByEmail($post_data['email']);

           //si on a un retour négatif
           if(is_null($user)){
               $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
           }else {
            if(!password_verify($this->validInput($post_data['password']),$user->password)){
                $form_result->addError(new FormError('Email et/ou mot de passe incorrect'));
            }
           }
        }
        //si on a des erreurson les stock en session et on renvoie vers la page de connexion
        if($form_result->hasErrors()){
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/connexion');
        }

        //si tout est OK on stock l'utilisateur en session et on le redirige vers la page d'accueil
        $user->password = '';
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        self::redirect('/');

    }

    public function registerForm()
    {
        $view = new View('auth/register');

        $view_data = [
            //permet de recupérer les message d'erreurs du formulaire (s'il y en a)
            'form_result' => Session::get(Session::FORM_RESULT)
        ];

        $view->render($view_data);
    }

    public function register(ServerRequest $request)
    {
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();
        

        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])

        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } elseif ($data_form['password'] !== $data_form['password_confirm']) {
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
        } elseif (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } elseif (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre'));
        } elseif ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('Cet email est déja utilisé'));
        } else { 
            $data_user = [
            'email' => $value = strtolower($this->validInput($data_form['email'])),
            'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
            'lastname' => $this->validInput($data_form['lastname']),
            'firstname' => $this->validInput($data_form['firstname']),
            'phone' => $this->validInput($data_form['phone'])
            ];
            $user = AppRepoManager::getRm()->getUserRepository()->addUser($data_user);
        }
        if ($form_result->hasErrors()){
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/inscription');
        }
        $user->password = '';
        Session::set(Session::USER, $user);
        Session::remove(Session::FORM_RESULT);
        self::redirect('/');
    }

    public function registerTeam(ServerRequest $request)
    {
        if (!AuthController::isAuth() || !AuthController::isAdmin()) self::redirect('/');
        $data_form = $request->getParsedBody();
        $form_result = new FormResult();
        $user = new User();
        

        if (
            empty($data_form['email']) ||
            empty($data_form['password']) ||
            empty($data_form['password_confirm']) ||
            empty($data_form['lastname']) ||
            empty($data_form['firstname']) ||
            empty($data_form['phone'])

        ) {
            $form_result->addError(new FormError('Veuillez renseigner tous les champs'));
        } elseif ($data_form['password'] !== $data_form['password_confirm']) {
            $form_result->addError(new FormError('Les mots de passe ne correspondent pas'));
        } elseif (!$this->validEmail($data_form['email'])) {
            $form_result->addError(new FormError('L\'email n\'est pas valide'));
        } elseif (!$this->validPassword($data_form['password'])) {
            $form_result->addError(new FormError('Le mot de passe doit contenir au moins 8 caractères, 1 majuscule, 1 minuscule et 1 chiffre'));
        } elseif ($this->userExist($data_form['email'])) {
            $form_result->addError(new FormError('Cet email est déja utilisé'));
        } else { 
            $data_user = [
            'email' => $value = strtolower($this->validInput($data_form['email'])),
            'password' => password_hash($this->validInput($data_form['password']), PASSWORD_BCRYPT),
            'lastname' => $this->validInput($data_form['lastname']),
            'firstname' => $this->validInput($data_form['firstname']),
            'phone' => $this->validInput($data_form['phone'])
            ];
            $user = AppRepoManager::getRm()->getUserRepository()->addTeam($data_user);
        }
        if ($form_result->hasErrors()){
            Session::set(Session::FORM_RESULT, $form_result);
            self::redirect('/admin/team/add');
        }
        Session::remove(Session::FORM_RESULT);
        self::redirect('/admin/team/list');
    }

    public static function isAuth(): bool
    {
        return !is_null(Session::get(Session::USER));
    }

    public static function isAdmin(): bool
    {
        return Session::get(SesSion::USER)->is_admin;
    }

    public function logout()
    {
        Session::remove(Session::USER);
        self::redirect('/');
    }
}
