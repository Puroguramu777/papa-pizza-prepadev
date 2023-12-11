<?php

namespace App\Controller;

use Core\Controller\Controller;
use Core\View\View;

class UserController extends Controller
{
    public function account(int $id)
    {
        $view = new View('user/account');

        $view->render();
    }
}