<?php 
namespace app\http\controllers;

use app\core\Controller;

class Auth extends Controller
{
    private $M_Auth;

    public function __construct()
    {
        Controller::set_layout('template_auth');
        $this->M_Auth = $this->model('M_Auth');

    }
    
    /**
     * Handle index login page
     * 
     */
    public function index()
    {

        if(IsLogin())
        {
            header('location:home');
        }

        $this->layoutView('auth/view_auth_index');
    }
    
    /**
     * Handle index register page
     * 
     */
    public function register()
    {
        $this->layoutView('auth/view_register_index');
    }
    
    /**
     * Check if the username is already used by another user
     * 
     * @return array $result
     */
    public function check_username()
    {
        $result = ['status' => false,'message' => null];
        
        $check_username = $this->M_Auth->_checkUsername();
        
        if($check_username)
        {
            $result = ['status' => true,'message' => 'Username already used'];
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Save new user account data
     * 
     * @return json $result
     */
    public function add_account()
    {
        $result = ['status' => false,'message' => null];
        
        $add_student = $this->M_Auth->_addAccount();
        
        if($add_student)
        {
            $result = 
            [
                'status'  => true,
                'message' => 'New account created successfully'
            ];
        }
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    /**
     * Handle index forgot password
     * 
     */
    public function forgot()
    {
        $this->layoutView('auth/view_forgot_password');
    }

    /**
     * Function to forget password, 
     * in order to update password
     * 
     */
    public function update_password()
    {
        $result = ['status' => false,'message' => null];

        $update_password = $this->M_Auth->_updatePassword();

        if($update_password)
        {
            $result = 
            [
                'status' => true,
                'message' => 'Change password successfully'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);        
    }

    /**
     * Check if the username and password are in the database
     * if they are the same and valid then please login
     * 
     * @return json 
     */ 
    public function login()
    {
        $result     = ['status' => false,'message' => 'Username or password do not match'];
        $username   = Post()->username;
        
        if(cek_cookie('password'))
        {
            $password = Decrypt(base64_decode(Post()->password));
        }
        else 
        {
            $password = base64_decode(Post()->password);
        }

        $user       = $this->M_Auth->_getDataByUsername($username);

        if($user['status'])
        {
            /* If the password or username does not match the data in the database, then give an error message */
            if(!password_verify($password,$user['data']['password']) || $username !== $user['data']['username'])
            {
                $result = 
                [
                    'status'    => false,
                    'message'   => 'Username or password do not match'
                ];
            }
            else 
            {
                $remember = Post()->remember;
                
                /* If true or checked then create a cookie */
                if($remember === "true")
                {
                    set_cookie('username',Encrypt($username),time() + 60 * 60 * 24 * 7,'','/');
                    set_cookie('password',Encrypt($password),time() + 60 * 60 * 24 * 7 ,'','/');
                }

                $result = 
                [
                    'status'    => true,
                    'message'   => 'Successfully'
                ];

                $session = 
                [
                    'fullname' => $user['data']['fullname'],
                    'id'       => $user['data']['id'],
                    'login'    => 1,
                ];  

                /* Update some column in table mst_user when user login */
                $this->M_Auth->_upadateLogin($user['data']['id']);

                /* Create log login */
                EventLoger('Auth','Login','User login to app',$user['data']['id']);

                set_userdata($session);
            }
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

}