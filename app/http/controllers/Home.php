<?php 
namespace app\http\controllers;

use app\core\Controller;

class Home extends Controller
{
    private $M_Home;

    public function __construct()
    {
        $this->M_Home = $this->model('M_Home');
    }

    public function index()
    {
        $this->layoutView('home/view_home_index');
    }
}