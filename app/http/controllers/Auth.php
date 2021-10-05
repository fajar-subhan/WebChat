<?php 
namespace app\http\controllers;

use app\core\Controller;

class Auth extends Controller
{
    private $M_Auth;

    public function __construct()
    {
        $this->M_Auth = $this->model('M_Auth');
    }
    
    /**
     * Handle index login page
     * 
     */
    public function index()
    {
        $this->onlyView('auth/view_auth_index');
    }

    /**
     * Handle index register page
     * 
     */
    public function register()
    {
        $this->onlyView('auth/view_register_index');
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
        $this->onlyView('auth/view_forgot_password');
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

}