<?php

namespace app\http\controllers;

use app\core\Controller;

class Home extends Controller
{
    /**
     * Model M_Home
     * 
     * @var object 
     */
    private $M_Home;

    /** 
     * Model M_Auth
     * 
     */
    private $M_Auth;
    

    public function __construct()
    {
        self::set_layout('template_home');

        $this->M_Home = $this->model('M_Home');
        $this->M_Auth = $this->model('M_Auth');

        if(!IsLogin())
        {
            header('location:' . BASE_URL);
        }
    }

    public function index()
    {
        $this->layoutView('home/view_home_index');
    }

    /**
     * This method is used to list contact data
     * 
     * @return string [name | avatar image | status online | icon]
     */
    public function getDataListContact()
    {
        $result = ['status' => false, 'data' => null];
        
        $user   = $this->M_Home->getDataListContact();

        if($user['status'])
        {
    
            $data = '';

            foreach($user['data'] as $key => $val) 
            {
                switch($val['stts_online'])
                {
                    case 'Online'   : 
                        $icon = 'online_icon';
                    break;

                    case 'Offline'  : 
                        $icon = 'offline_icon';
                    break;

                    case  'Outside' : 
                        $icon = 'outside_icon';
                    break;

                    case 'Busy'     : 
                        $icon = 'busy_icon';
                    break;
                }

                $data .= '<li> <div class="d-flex bd-highlight" id="'.Encrypt($val['id']).'"> <div class="img_cont"> <img src="'.BASE_URL.'assets/images/contacts/'.$val['photo'].'" class="rounded-circle user_img"> <span class="'.$icon.'"></span> </div><div class="user_info"> <span>'.$val['fullname'].'</span> <p>Kalid is '.strtolower($val['stts_online']).'</p></div></div></li>';
            }

            $result = ['status' => true,'data' => $data];
            
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * This method is used to exit the application and delete the session
     * 
     */
    public function logout()
    {
        $result = ['status' => false,'url' => null];

        /* Update some column in table mst_user when user login */
        $update_logout = $this->M_Auth->_upadateLogout(Post()->id);

        if($update_logout)
        {
            session_destroy();

            $result = ['status' => true,'url' => BASE_URL];

            /* Create log logout */
            EventLoger('Auth','Logout','User logout to app',userdata('id'));

        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
