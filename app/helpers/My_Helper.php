<?php 
/**
 * All Helpers
 *
 * @subpackage	Helpers
 * @category	All Helpers
 * @author		Fajar Subhan
 * @since       v1.0
 * 
 */

use app\core\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

/**
 * Clean the incoming data from the input form and retrieve 
 * the data via the post method
 *
 * @return object $post
*/
if(!function_exists('Post'))
{
    function Post()
    {
        $post = null;

        $method = $_SERVER['REQUEST_METHOD'];
        
        if($method == 'POST')
        {
            foreach($_POST as $key => $value)
            {
                $post[$key] = htmlentities(strip_tags(trim(filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS))));
            }
        }
        
        return (object)$post;
    }
}


/**
 * Clean the incoming data from the input form and retrieve 
 * the data via the get method
 *
 * @return object $get
*/
if(!function_exists('Get'))
{
    function Get()
    {
        $get = null;

        $method = $_SERVER['REQUEST_METHOD'];

        if($method == "GET")
        {
            foreach($_GET as $key => $value)
            {
                $get[$key] = htmlentities(strip_tags(trim(filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS))));
            }
        }

        return (object)$get;
    }
}

/** 
 * debug data 
 * 
 * @return array 
*/
if(!function_exists('ShowArray'))
{
    function ShowArray($data = "")
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}

/**
 * Take the user's ip address
 * 
 * @return string $ip_address
 */
if(!function_exists('GetIP'))
{
    function GetIP()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else 
        {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        return $ip_address;
    }
}

/**
 * Retrieve the browser information used by the user
 * 
 * @return string $browser
 */
if(!function_exists('GetBrowser'))
{
    function GetBrowser()
    {
        $USER_AGENT = [];

        foreach($_SERVER as $key => $val)
        {
            // Takes only strings that start with HTTP_
            if(!strncmp("HTTP_",$key,5))
            {
                $USER_AGENT[$key] = $val;
            }
        }

        // Google Chrome 
        if(strpos($USER_AGENT['HTTP_USER_AGENT'],'Chrome') != false)
        {
            $browser = "Google Chrome";
        }
        // Internet Explore 
        else if(strpos($USER_AGENT['HTTP_USER_AGENT'],'MSIE') != false)
        {
            $browser = "Internet Explore";
        }
        // Mozila Firefox
        else if(strpos($USER_AGENT['HTTP_USER_AGENT'],'Firefox') != false)
        {
            $browser = "Mozila Firefox";
        }
        // Safari
        else if(strpos($USER_AGENT['HTTP_USER_AGENT'],'AppleWebKit') != false)
        {
            $browser = "Safari";
        }
        // Unknown Browser
        else 
        {
            $browser = "Unknown Browser";
        }

        return $browser;
    }
}

/**
 * Retrieve the operating system information the user is using
 * 
 * @return string $os
 */
if(!function_exists('GetOS'))
{
    function GetOS()
    {
        $USER_AGENT = [];
        
        foreach($_SERVER as $key => $value)
        {
            // Takes only strings that start with HTTP_
            if(!strncmp("HTTP_",$key,5))
            {
                $USER_AGENT[$key] = $value;
            }
        }

        $os      = "Unknown Operating System";
        
        $os_list = 
        [
            'Windows 10'                =>  'windows nt 10.0',
            'Windows 8'                 =>  'windows nt 6.2',
            'Windows 7'                 =>  'windows nt 6.1',
            'Windows XP'                =>  'windows nt 5.1',
            'Windows NT 4.0'            =>  'windows nt 4.0',
            'Windows Vista'             =>  'windows nt 6.0',
            'Windows 2000'              =>  'windows nt 5.0',
            'Windows 2000 sp1'          =>  'windows nt 5.01',  
            'Windows Server 2003'       =>  'windows nt 5.2',
            'Windows 98'                =>  'windows 98',
            'Windows (version unknown)' =>  'windows',
            'Open BSD'                  =>  'openbsd',
            'Linux'                     =>  'linux',
            'Sun OS'                    =>  'sunos',
            'Mac OSX Beta (Kodiak)'     =>  'mac os x beta',
            'Mac OSX Cheetah'           =>  'mac os x 10.0',
            'Mac OSX Puma'              =>  'mac os x 10.1',
            'Mac OSX Jaguar'            =>  'mac os x 10.2',
            'Mac OSX Panther'           =>  'mac os x 10.3',
            'Mac OSX Tiger'             =>  'mac os x 10.4',
            'Mac OSX Leopard'           =>  'mac os x 10.5',
            'Mac OSX Snow Leopard'      =>  'mac os x 10.6',
            'Mac OSX Lion'              =>  'mac os x 10.7',
            'Mac OSX (version unknown)' =>  'mac os x',
            'Mac OS (classic)'          =>  '(mac_powerpc)|(macintosh)',
            'QNX'                       =>  'qnx',
            'BeOS'                      =>  'beos',
            'OS/2'                      =>  'os/2',
            'SearchBot'                 =>  '(nuhk)|(googlebot)|(yammybot)|(openbot)|(slurp)|(msnbot)|(ask jeeves/teoma)|(ia_archiver)'
        ];

        
        if(is_array($os_list))
        {
            $USER_AGENT = strtolower($USER_AGENT['HTTP_USER_AGENT']);
            if(!empty($USER_AGENT))
            {
                foreach($os_list as $os_info => $match)
                {
                    // Check the pattern of the array variables os_list and HTTP_USER_AGENT
                    if(preg_match("/$match/i",$USER_AGENT))
                    {
                        $os = $os_info;
                        break;
                    }
                }
            }
        }

        return $os;
    }
}

/**
 * Retrieving information and processing data so that it can be used for encryption purpose
 * 
 * @return array $data
 */
if(!function_exists("Security"))
{
    function Security()
    {
        $data = [];

        /* Read file security.ini and get config encription */
        $security =  parse_ini_file("Security.ini");
        
        /* Key to unlock encryption */
        $key      = hash("sha256",$security['encription_key']);
                
        /* Cipher_algo AES-256-CBC */
        $algo     = $security['encription_algo'];

        /* 
         * A non-null initialization vector. 
         * For the length of the character depending on the method used, 
         * 
         */
        $ivlength = openssl_cipher_iv_length($security['encription_algo']);
        $iv       = substr(hash("sha256",$security['encription_iv']),0,$ivlength);
        
        $data = 
        [
            'key'   => $key,
            'algo'  => $algo,
            'iv'    => $iv
        ];

        if(is_array($data))
        {
            return $data;
        }
    }
}

/**
 * Create a function to encrypt a data using openssl_encrypt
 * 
 * @param   string $data
 * @return  string $encrypt
 * @link    https://www.php.net/manual/en/function.openssl-encrypt.php
 */
if(!function_exists('Encrypt'))
{
    function Encrypt($data)
    {
        /* Retrieve key,algo,and iv information */
        $sec      = Security();
        
        /* Time to encrypt with openssl_encrypt */
        $encrypt  = base64_encode(openssl_encrypt($data,$sec['algo'],$sec['key'],0,$sec['iv']));

        return $encrypt;
    }
}

/**
 * Create a function to decrypt a data using openssl_decrypt
 * 
 * @param   string $data
 * @return  string $decrypt
 * @link    https://www.php.net/manual/en/function.openssl-decrypt
 */
if(!function_exists('Decrypt'))
{
    function Decrypt($data)
    {
        /* Retrieve key,algo,and iv information */
        $sec      = Security();

        /* Time to decrypt with openssl_decrypt */
        $decrypt  = openssl_decrypt(base64_decode($data),$sec['algo'],$sec['key'],0,$sec['iv']);

        return $decrypt;
    }
}

/**
 * Create a log to view user activity,
 * and enter it into the user_activity_log table
 * 
 * @param string $module : module name 
 * @param string $name   : activity name
 * @param string $desc   : User login | User logout 
 * @param string $userid : user id 
 */
if(!function_exists("EventLoger"))
{
    function EventLoger($module = "",$name = "",$desc = "" ,$userid = "")
    {
        $FS = new Model();
            
        $FS->set('user_activity_module',$module);
        $FS->set('user_id',$userid);
        $FS->set('user_activity_name',$name);
        $FS->set('user_activity_desc',$desc);
        $FS->set('user_activity_address',GetIP());
        $FS->set('user_activity_browser',GetBrowser());
        $FS->set('user_activity_os',GetOS());
        $FS->set('user_activity_date',date('Y-m-d H:i:s'));
        $FS->insert('user_activity_log');
    }
}

/**
 * Setting session
 * 
 * @param array $session
 */
if(!function_exists('set_userdata'))
{
    function set_userdata($session = [])
    {
        if(is_array($session))
        {
            foreach($session as $key => $value)
            {
                $_SESSION[$key] = $value;
            }            
        }
    }
}

/**
 * Retrieve session data
 * 
 * @param string $session
 */
if(!function_exists('userdata'))
{
    function userdata($session)
    {
        if(!empty($_SESSION))
        {
            return $_SESSION[$session];
        }
        else 
        {
            return null;
        }
    }
}

/**
 * This helper function gives you friendlier syntax to set browser cookies.
 * 
 * @param mixed  $name   Cookie name or associative array of all of the parameters available to this function
 * @param string $value  Cookie value
 * @param int    $expire Number of seconds until expiration
 * @param string $domain Cookie domain 
 * @param string $path   Cookie path
 * @return vaid
 */
if(!function_exists('set_cookie'))
{
    function set_cookie($name,$value = "",$expire = "",$domain = "",$path = "/")
    {
        if(!empty($name) && !empty($value))
        {
            setcookie($name,$value,$expire,$path,$domain);
        }
    }
}

/**
 * This helper function gives your friendlier syntax to get browser cookies
 * 
 * @param string $index Cookie name
 * @return string
 */
if(!function_exists('get_cookie'))
{
    function get_cookie($index)
    {
        return !empty($_COOKIE[$index]) ? $_COOKIE[$index] : '';
    }
}

/** 
 * This helper function provides a more friendly syntax 
 * to check if there are cookies in the browser
 * 
 * If no cookie then 0
 * If there is a cookie then 1
 * 
 * @param string $index
 * @return int 
*/
if(!function_exists('cek_cookie'))
{
    function cek_cookie($index)
    {
        return empty($_COOKIE[$index]) ? false : true;
    }
}

/**
 * Check if the user is logged in or not
 * If login is true
 * If not logged in false
 *  
 *  @return boolean $login 
 */
if(!function_exists('IsLogin'))
{
    function IsLogin()
    {
        if(userdata('login') == 1)
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
}

/**
 * Taking online status
 * 
 * Online | Offline | Outside | Busy 
 * 
 * @return array $result 
 */
if(!function_exists('Status'))
{
    function Status()
    {
        $result = [];

        $FS = new Model();
        
        $FS->reset_select();
        $FS->select('
        a.status_code as code,
        a.status_name as name
        ');
        $FS->from('ref_status_online a');
        $FS->where('a.status_active',1);
        $FS->order_by('a.status_order','ASC');
        $FS->get();

        if($FS->num_rows() > 0)
        {
            foreach($FS->result_array() as $rows)
            {
                $result[$rows['code']] = $rows['name'];
            }
        }

        return $result;
    }
}

/**
 * Retrieve user profile data based on 
 * session user id who is currently logged in
 * 
 * @param int $user_id
 * @return array $result
 */
if(!function_exists('GetProfile'))
{
    function GetProfile($user_id)
    {

        $result = [];

        $FS = new Model();

        $FS->reset_select();
        $FS->select('
        a.user_full_name as fullname,
        a.user_name as username,
        a.user_photo as photo,
        a.user_status_online as online,
        b.status_name');
        $FS->from('mst_user a');
        $FS->join('ref_status_online b','b.status_code = a.user_status_online','inner');
        $FS->where('a.user_active',1);
        $FS->where('a.user_id',$user_id);
        $FS->get();

        if($FS->num_rows() > 0)
        {
            foreach($FS->result_array() as $rows)
            {
                $result          = $rows;
                $result['photo'] = Encrypt($rows['photo']);
            }
        }

        return $result;
    }
}

/**
 * To provide a contact profile status icon in the 
 * css class based on the session user id
 * 
 * online_icon | offline_icon | outside_icon | busy_icon
 * 
 * @param int $online status user online
 */
if(!function_exists('StatusIcon'))
{
    function StatusIcon($online)
    {
        $result = "";

        switch($online)
        {
            case 01 : 
                $result = "online_icon";
            break;
            case 02 : 
                $result = "offline_icon";
            break;
            case 03 : 
                $result = "outside_icon";
            break;
            case 04 : 
                $result = "busy_icon";
            break;
        }

        return $result;
    }
}