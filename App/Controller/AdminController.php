<?php 

namespace App\Controller;

use Core\View\View;
use Core\Controller\Controller;

class AdminController extends Controller
{


    public function account()
    {

        if(!AuthController::isAuth() || !AuthController::isAdmin() ) self::redirect('/');
        $view = new View ('admin/admin');

        $view->render();
    }
}