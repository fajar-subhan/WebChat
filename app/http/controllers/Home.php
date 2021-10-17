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
     * @return json $result 
     */
    public function logout()
    {
        $result = ['status' => false,'url' => null];

        /* Update some column in table mst_user when user login */
        $update_logout = $this->M_Auth->_upadateLogout(Decrypt(Post()->id));

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

    /**
     * This method is used to change the user's status bar
     * 
     * Online | Offline | Outside | Busy Status
     * 
     *  @return json $result
     */
    public function status()
    {
        $result = ['status' => false,'data' => null];

        $status = Decrypt(Post()->status);

        $update_status = $this->M_Auth->_updateStatus($status);

        if($update_status)
        {
            $result = ['status' => true,'data' => strtolower($update_status)];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * This is used to retrieve form data when the profile button is clicked
     * 
     * @return json
     */
    public function getDataProfile()
    {
        $result  = ['status' => false,'data' => null];

        $id      = Decrypt(Post()->id);

        $profile = $this->M_Home->_getDataProfile($id);

        if($profile)
        {
            $result = ['status' => true,'data' => $profile];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Update the profile data of each member user based on their id
     * 
     * @return json $result 
     */
    public function updateProfile()
    {
        $result = ['status' => false,'message' => 'Data failed to update'];

        if(!empty($_FILES['photo']))
        {
            /* Allowed extensions : jpg | png */
            $allowed_extension = ['jpg','png'];

            $filename    = str_replace(['.jpg','.png'],'',$_FILES['photo']['name'] . "_" . md5(date('YmdHis')));
            $extension   = pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION);
            $output_name = urlencode($filename . '.' . $extension);

            $file_size   = $_FILES['photo']['size'];
            $file_tmp    = $_FILES['photo']['tmp_name'];

            /* If there is a file extension is allowed */
            if(in_array($extension,$allowed_extension) == true)
            {
                /* Set the maximum uploaded file size to 1mb */
                if($file_size < 1000000)
                {
                    move_uploaded_file($file_tmp,'assets/images/contacts/' . $output_name);

                    $upload = $this->M_Home->_updateProfile($output_name);
                    
                    if($upload)
                    {
                        $result = 
                        [
                            'status'    => true,
                            'message'   => 'Data updated successfully'
                        ];

                        /**
                         * If it is successfully updated, first delete the photo 
                         * that was previously on the server so that it doesn't 
                         * accommodate a lot of the previous photo
                         * 
                         */
                        if(!empty(Decrypt(Post()->prevPhoto)))
                        {
                            /**
                             * Check if the previous photo file exists, 
                             * if there is then delete the photo first
                             * 
                             */
                            if(file_exists(ASSETS_IMAGES . 'contacts/' . Decrypt(Post()->prevPhoto)) && (Decrypt(Post()->prevPhoto) != 'default.jpg'))
                            {
                                $path = ASSETS_IMAGES . 'contacts/' . Decrypt(Post()->prevPhoto);

                                /* Remove previous photo file */
                                unlink($path);
                            }
                        }
                    }
                }
                else 
                {
                    $result = ['status' => false,'message' => 'Maximum upload file 1 mb'];
                }
            }
            else 
            {
                $result = ['status' => false,'message' => 'Invalid file format'];
            }
        }
        else 
        {
            $upload = $this->M_Home->_updateProfile();

            if($upload)
            {
                $result = ['status' => true,'message' => 'Data updated successfully'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Check if the username is already in use by another user before updating.
     * If the username is the same as the previous username then allow it but 
     * if it is the same as the existing one then reject it before updating
     * 
     * @return array $result
     */
    public function check_username_update()
    {
        $result = ['status' => false,'message' => null];
        
        $check_username_update = $this->M_Home->_checkUsernameUpdate();

        if($check_username_update)
        {
            $result = ['status' => true,'message' => 'Username already used'];
        }
    
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
