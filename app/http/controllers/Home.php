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
        $result = ['status' => "false", 'data' => null];

        $user   = $this->M_Home->_getDataListContact();

        if($user['status'])
        {
    
            $data = '';

            /**
             * This comes from my session id when logging in
             * 
             * @var string $id
             */
            $myID       = userdata('id');
            $last_date  = "";

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

                /**
                 * This comes from the id belonging to each contact list.
                 * Which comes from mst_user.user_id
                 * 
                 * @var string $id
                 */
                $contactID = $val['id'];

                /**
                 * Retrieve the last message data
                 * 
                 * @var array $last_chat
                 */
                $last_chat  = $this->M_Home->_getDataLastChat($contactID,$myID);  

                $chat_read  = "";

                if($last_chat)
                {

                    /**
                     * Retrieve data when someone is typing into our account
                     * 
                     * @var int $typing
                     */
                    $typing     = $this->M_Home->_getDataTyping($contactID,$myID);
                
                    if($typing > 0)
                    {
                        $last_message = "typing ......";
                    }
                    else 
                    {
                        foreach($last_chat as $key => $value)
                        {

                            switch ($last_chat['chat_type'])
                            {
                                case 'images' : 
                                    $last_message = '<span class="chat-meta"><i class="fa fa-camera"></i> Images</span>';
                                    break;
                                case 'files'   : 
                                    $last_message = '<span class="chat-meta"><i class="fa fa-paperclip"></i> File</span>';
                                    break;
                                case 'text'   : 
                                    $last_message = substr($last_chat['content'],0,30);
                                break;
                            }
                            
                            $chat_read  = '<span>&#10004;</span>'; 

                
                            /**
                             * Two blue ticks : The recipient has read your message.
                             */
                            if
                                (
                                    $last_chat['sender_id']   == userdata('id') && 
                                    $last_chat['receive_id']  == $contactID &&
                                    $last_chat['chat_read']   == 1
                                )
                            {
                                $chat_read = '<span style="color:#34b7f1 !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                            }
            
                            /**
                             * Tick ​​two grays: The message has been delivered to the recipient's phone 
                             * but the received message has not been read.
                             * 
                             */
                            else if
                                (
                                    $last_chat['sender_id']   == userdata('id') &&
                                    $last_chat['receive_id']  == $contactID &&
                                    $last_chat['chat_read']   == 0
                                )
                            {
                                $chat_read = '<span style="color:rgba(255, 255, 255, 0.5) !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                            }
                            else 
                            {
                                $chat_read = '';
                            }

                            $last_date    = date('H:i',strtotime($last_chat['chat_date']));
                        }
                    }
                }
                else 
                {
                    $last_message = "";
                    $last_date    = "";
                }

                /**
                 * If there is a new incoming message, 
                 * give a notification in the form of a number symbol
                 * 
                 */
                $new_message = $this->M_Home->_getDataNewMessage($contactID,$myID);

                if($new_message > 0)
                {
                    $new_message = $new_message;
                }
                else 
                {
                    $new_message = "";
                }

                $data .= '
            <li class="list-contact" id="'.Encrypt($val['id']).'">
                <div class="d-flex bd-highlight">
                    <div class="img_cont"> <img src="'.BASE_URL.'assets/images/contacts/'.$val['photo'].'" class="rounded-circle user_img"> <span class="'.$icon.'"></span> </div>
                        <div class="user_info">
                            <span class="user_info_username">'.$val['fullname'].'</span>
                            <span class="last_message">' . $chat_read . ' ' . $last_message .'</span>

                            <span class="time-meta pull-right">
                            '.$last_date.'							
                            </span>

                            <span class="badge badge-success">'.$new_message.'</span>

                        </div>
                    </div>

                </div>
                
                </div>
            </li>';
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
     * @return json $result
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
    
    /**
     * This method is useful for displaying chat content
     * 
     * @return json $result
     */
    public function showChat()
    {
        $result = ['status' => false,'content' => null];

        $show_chat = $this->M_Home->_showChat();
    
        if($show_chat)
        {
            $result = "";

            /**
             * This comes from my session id when logging in
             * 
             * @var string $id
             */
            $myID = userdata('id');

            /**
             * This comes from the id belonging to each contact list.
             * Which comes from mst_user.user_id
             * 
             * @var string $id
             */
            $contactID = Decrypt(Post()->id);

            /**
             * If the message has been read, 
             * it is a sign that the message has been read
             * 
             */
            $this->M_Home->_ChatRead($contactID,$myID);

            /**
             * The message has been sent.
             * 
             * @var string $chat_read
             */
            
            foreach($show_chat as $rows)
            {           
                $color      = $rows['sender_id'] == userdata('id') ?  'msg_cotainer' : 'msg_cotainer_send';
                $images     = ASSETS_IMAGES . 'contacts/' . $rows['photos'];
                $position   = $rows['sender_id'] == userdata('id') ? 'justify-content-end' : 'justify-content-start';
                $time       = date('H:i',strtotime($rows['chat_date']));
                
                $chat_read  = '<span>&#10004;</span>'; 

                switch ($rows['chat_type'])
                {
                    case 'text'     : 
                        $content = $rows['content'];
                    break;

                    case 'files'    :
                        $content = '<a target="_blank" href="'.ASSETS_IMAGES.'../files/'.$rows['content'].'" download>'.$rows['content'].'</a>';
                    break;

                    case 'images'   :
                        $path    = ASSETS_IMAGES . 'chat/' . $rows['content'];
                        $content = '<img style="width:100%" height="200" src="'.$path.'">'; 
                    break;
                }

                /**
                 * Two blue ticks : The recipient has read your message.
                 */
                if
                    (
                        $rows['sender_id']   == userdata('id') && 
                        $rows['receive_id']  == $contactID &&
                        $rows['chat_read']   == 1
                    )
                {
                    $chat_read = '<span style="color:#34b7f1 !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }

                /**
                 * Tick ​​two grays: The message has been delivered to the recipient's phone 
                 * but the received message has not been read.
                 * 
                 */
                else if
                    (
                        $rows['sender_id']   == userdata('id') &&
                        $rows['receive_id']  == $contactID &&
                        $rows['chat_read']   == 0
                    )
                {
                    $chat_read = '<span style="font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }
                else 
                {
                    $chat_read = '';
                }


                $result .= ' <div class="d-flex '. $position .' mb-4"> <div class="'. $color .'"> '.$content.' <span class="msg_time_send">'.$time . ' ' . $chat_read.'</span> </div><div class="img_cont_msg"> <img src="'. $images . '" class="rounded-circle user_img_msg"> </div></div>';
            }


            $result = ['status' => true,'content' => $result];

        }   

        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    /**
     * This method used to display profile photos of chat friends
     * 
     * @return json $result
     */
    public function profileFriends()
    {
        $result = ['status' => false,'content' => null];

        $profile_friends = $this->M_Home->_profileFriends();
    
        if($profile_friends)
        {
            $images     =  ASSETS_IMAGES . 'contacts/' . $profile_friends['photo'];
            
            switch($profile_friends['stts_online'])
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

            $content = '<div class="d-flex bd-highlight friends" id="'.Encrypt($profile_friends['id']).'"> <div class="img_cont"> <img src="'.$images.'" class="rounded-circle user_img"> <span class="'.$icon.'"></span> </div><div class="user_info"> <span>'.$profile_friends['fullname'].'</span> <p>'.$profile_friends['stts_online'].'</p></div></div>';

            $result = ['status' => true,'content' => $content];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    
    /**
     * This method is used to create a list of contract data based on the search input
     * 
     * @return string [name | avatar image | status online | icon]
     */
    public function getDataListContactSearch()
    {
        $result = ['status' => false,'data' => null];

        $user   = $this->M_Home->_getDataBySearch();

        if($user['status'])
        {
            $data   = "";

            $chat_read  = "";

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

                /**
                 * This comes from the id belonging to each contact list.
                 * Which comes from mst_user.user_id
                 * 
                 * @var string $id
                 */
                $contactID = $val['id'];

                /**
                 * Two blue ticks : The recipient has read your message.
                 */
                if
                    (
                        $val['sender_id']   == userdata('id') && 
                        $val['receive_id']  == $contactID &&
                        $val['chat_read']   == 1
                    )
                {
                    $chat_read = '<span style="color:#34b7f1 !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }

                /**
                 * Tick ​​two grays: The message has been delivered to the recipient's phone 
                 * but the received message has not been read.
                 * 
                 */
                else if
                    (
                        $val['sender_id']   == userdata('id') &&
                        $val['receive_id']  == $contactID &&
                        $val['chat_read']   == 0
                    )
                {
                    $chat_read = '<span style="color:rgba(255, 255, 255, 0.5) !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }
                else 
                {
                    $chat_read = '';
                }

        
                $data .= ' <li class="list-contact" id="'.Encrypt($val['id']).'"> 
                <div class="d-flex bd-highlight"> <div class="img_cont"> 
                <img src="'.BASE_URL.'assets/images/contacts/'.$val['photo'].'" class="rounded-circle user_img"> 
                <span class="'.$icon.'"></span> 
                </div>
                <div class="user_info"> 
                <span class="user_info_username">'.$val['fullname'].'</span> 
                <span class="last_message">'.$chat_read . ' ' . str_replace(base64_decode(Post()->search),'<b>'. base64_decode(Post()->search) . '</b>',substr($val['content'],0,30)).'</span> <span class="time-meta pull-right"> '.date('H:i',strtotime($val['chat_date'])).' </span> </div></div></div></div></li>';
            }
            
            $result = ['status' => true,'data' => $data];
        }

        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * When the button is entered, the chat message process
     * 
     */
    public function sendChat()
    {
        $result = ['status' => false,'content' => null];
        $send = $this->M_Home->_sendChat('text');

        $content = "";
        /**
         * This comes from the id belonging to each contact list.
         * Which comes from mst_user.user_id
         * 
         * @var string $id
         */
        $contactID = Decrypt(Post()->contactID);

        if($send)
        {
            $show_chat = $this->M_Home->_lastShowChat();
        
            foreach($show_chat as $rows)
            {                           
                $color      = $rows['sender_id'] == userdata('id') ?  'msg_cotainer' : 'msg_cotainer_send';
                $images     =  ASSETS_IMAGES . 'contacts/' . $rows['photo'];
                $position   = $rows['sender_id'] == userdata('id') ? 'justify-content-end' : 'justify-content-start';
                $date       = date('H:i',strtotime($rows['chat_date']));

                $chat_read  = '<span>&#10004;</span>'; 

                
                /**
                 * Two blue ticks : The recipient has read your message.
                 */
                if
                    (
                        $rows['sender_id']   == userdata('id') && 
                        $rows['receive_id']  == $contactID &&
                        $rows['chat_read']   == 1
                    )
                {
                    $chat_read = '<span style="color:#34b7f1 !important;font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }

                /**
                 * Tick ​​two grays: The message has been delivered to the recipient's phone 
                 * but the received message has not been read.
                 * 
                 */
                else if
                    (
                        $rows['sender_id']   == userdata('id') &&
                        $rows['receive_id']  == $contactID &&
                        $rows['chat_read']   == 0
                    )
                {
                    $chat_read = '<span style="font-size: 10px !important;">&#10004;&#10004;</span>'; 
                }
                else 
                {
                    $chat_read = '';
                }

                $content .= ' <div class="d-flex '. $position .' mb-4"> <div class="'. $color .'"> '.$rows['content'].' <span class="msg_time_send">'.$date . ' ' . $chat_read.'</span> </div><div class="img_cont_msg"> <img src="'. $images . '" class="rounded-circle user_img_msg"> </div></div>';
            }


            

            $result = ['status' => true,'content' => $content];

        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Give the status of typing to chat friends
     * 
     * @return void
     */
    public function statusTyping()
    {
        /* First check if someone has typed */
        $check = $this->M_Home->_checkTyping();

        if($check == 0)
        {
            /**
             * if it's empty then insert it to give status to chat friends that I'm typing
             * 
             */
            $this->M_Home->_statusTyping();
        }
    }

    /**
     * When you are no longer typing then delete the words 'is typing'
     * 
     * @return void
     */
    public function deleteTyping()
    {
        $this->M_Home->_deleteTyping();
    }

    /**
     * Send a picture message
     * 
     * @return json
     */
    public function uploadImage()
    {
        $result = ['status' => 0,'message' => 'Image failed to send'];

        if(!empty($_FILES['photo']))  
        {
            /* Allowed extensions : jpg | png | jpeg */
            $allowed_extension = ['jpg','png' , 'jpeg'];
            
            /* Extension */
            $extension      = pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION);
            
            $filename       = "images_" . md5(date('Y-m-d H:i:s')) . '.' . $extension;

            $file_size      = $_FILES['photo']['size'];
            $file_tmp       = $_FILES['photo']['tmp_name'];

            /* If there is a file extension is allowed */
            if(in_array($extension,$allowed_extension) == true)
            {
                /* Set the maximum uploaded file to 1mb */
                if($file_size < 1000000)
                {
                    move_uploaded_file($file_tmp,'assets/images/chat/' . $filename );

                    $upload = $this->M_Home->_uploadImage($filename);

                    if($upload)
                    {
                        $result = [ 'status' => 1];
                    }
                }
                else 
                {
                    $result = ['status' => 0,'message' => 'Maximum upload file 1 mb'];
                }
            }
            else 
            {
                $result = ['status' => 0,'message' => 'Invalid file format'];
            }
        }  

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /**
     * Send a file message
     * 
     * @return json 
     */
    public function uploadFile()
    {
        $result = ['status' => 0,'message' => 'Sending file failed'];

        if(!empty($_FILES['file']))  
        {
            /* Allowed extensions : pdf | zip | mp4 | rar */
            $allowed_extension = ['pdf','zip','mp4','rar'];
            
            /* Extension */
            $extension      = pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
            
            $filename       = "files_" . md5(date('Y-m-d H:i:s')) . '.' . $extension;

            $file_size      = $_FILES['file']['size'];
            $file_tmp       = $_FILES['file']['tmp_name'];

            /* If there is a file extension is allowed */
            if(in_array($extension,$allowed_extension) == true)
            {
                /* Set the maximum uploaded file to 2mb */
                if($file_size < 2000000)
                {
                    move_uploaded_file($file_tmp,'assets/files/' . $filename );

                    $upload = $this->M_Home->_uploadFile($filename);

                    if($upload)
                    {
                        $result = [ 'status' => 1];
                    }
                }
                else 
                {
                    $result = ['status' => 0,'message' => 'Maximum upload file 2 mb'];
                }
            }
            else 
            {
                $result = ['status' => 0,'message' => 'Invalid file format'];
            }
        }  

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
