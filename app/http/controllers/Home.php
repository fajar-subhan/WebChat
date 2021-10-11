<?php

namespace app\http\controllers;

use app\core\Controller;

class Home extends Controller
{
    private $M_Home;

    public function __construct()
    {
        self::set_layout('template_home');
        $this->M_Home = $this->model('M_Home');
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
        
        $user           = $this->M_Home->getDataListContact();

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

                $data .= '
                    <li>
                        <div class="d-flex bd-highlight" id="'.Encrypt($val['id']).'">
                            <div class="img_cont">
                                <img src="'.BASE_URL.'assets/images/contacts/'.$val['photo'].'" class="rounded-circle user_img">
                                <span class="'.$icon.'"></span>
                            </div>
                            <div class="user_info">
                                <span>'.$val['fullname'].'</span>
                                <p>Kalid is '.strtolower($val['stts_online']).'</p>
                            </div>
                        </div>
                    </li>
                    ';
            }

            $result = ['status' => true,'data' => $data];
            
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
