<?php 
namespace app\http\controllers;

use app\core\Controller;

class Auth extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('M_Auth');
    }

    public function index()
    {
        $this->onlyView('auth/view_auth_index');
    }
}