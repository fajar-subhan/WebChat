<?php 
namespace app\models;

use app\core\Model;

class M_Home extends Model
{
    /**
     * Primary table name
     * 
     * @var string $table 
     */
    private $table  = "mst_chat a";

    /**
     * To put the model object along with the currently 
     * accessed objects And to fetch the query builder
     * 
     * @@var object $db 
     */
    private $db;

    public function __construct()
    {
        $this->db = parent::__construct();
    }

    /**
     * Take a list of registered user data but open it 
     * for the session user id who is currently logged in
     * 
     * @return array $result
     */
    public function _getDataListContact()
    {
        $result = ['status' => false,'data' => null];

        $this->db->reset_select();
        $this->db->select('
        a.user_id as id,
        a.user_full_name as fullname,
        a.user_name as username,
        a.user_photo as photo,
        b.status_name as stts_online
        ');
        $this->db->from('mst_user a');
        $this->db->join('ref_status_online b','a.user_status_online = b.status_code','inner');
        $this->db->where('a.user_id !=',userdata('id'));
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            foreach($this->db->result_array() as $rows)
            {
                $data[] = $rows;
            }

            $result = ['status' => true,'data' => $data];
        }

        return $result;
    }

    /**
     *  This is used to retrieve form data when the profile button is clicked
     *  
     * @param int $id 
     * @return array $result
     */
    public function _getDataProfile($id)
    {
        $result = [];

        $this->db->reset_select();
        $this->db->select('
        a.user_full_name as fullname,
        a.user_name as username,
        a.user_photo as photo
        ');
        $this->db->from('mst_user a');
        $this->db->where('a.user_id',$id);
        $this->db->where('a.user_active',1);
        $this->db->get();
        if($this->db->num_rows() > 0)
        {
            foreach($this->db->result_array() as $rows)
            {
                $result['fullname'] = $rows['fullname'];
                $result['username'] = $rows['username'];
                $result['photo']    = Encrypt($rows['photo']);
            }
        }

        return $result;
    }

    /**
     * Update the profile data of each member user based on their id
     *
     * @param string $filename
     * @return int $count
     */
    public function _updateProfile($filename = "")
    {
        $count = 0;

        $this->db->reset_select();
        $this->db->set('user_full_name',Post()->fullname);
        $this->db->set('user_name',Post()->username);
        
        if(!empty(Post()->password))
        {
            $this->db->set('user_password',password_hash(base64_decode(Post()->password),PASSWORD_DEFAULT));
        }

        if(!empty($filename))
        {
            $this->db->set('user_photo',$filename);
        }

        $this->db->set('user_updated_at',date('Y-m-d H:i:s'));
        $this->db->where('user_id',Decrypt(Post()->id));
        $this->db->update('mst_user');
        if($this->db->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }

    /**
     * Check if the username is already in use by another user before updating.
     * 
     * @return array $result
     */
    public function _checkUsernameUpdate()
    {
        $count = 0;

        $this->db->reset_select();
        $this->db->select('count(*) as total,a.user_name as username');
        $this->db->from('mst_user a');
        $this->db->where('a.user_name',Post()->username);
        $this->db->where('a.user_id !=',userdata('id'));
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            foreach($this->db->result_array() as $rows)
            {
                if($rows['username'] == Post()->username)
                {
                    $count = 0;
                }

                if($rows['total'] > 0)
                {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * This method is useful for displaying charts based 
     * on sender id and recipient id maybe vice versa
     * 
     * @return array $result
     */
    public function _showChat()
    {
        $result = [];

        /**
         * This comes from the id belonging to each contact list.
         * Which comes from mst_user.user_id
         * 
         * @var string $id
         */
        $contactID = Decrypt(Post()->id);

        /**
         * This comes from my session id when logging in
         * 
         * @var string $id
         */
        $myID = userdata('id');

        $sql = "SELECT 
        a.chat_id,
        a.chat_sender_id as sender_id,
        a.chat_receive_id as receive_id,
        a.chat_content as content,
        a.chat_read as chat_read,
        a.chat_type,
        a.chat_created_at as chat_date,
        b.user_photo as photos,
        b.user_status_online AS stts_online
        FROM {$this->table} 
        INNER JOIN mst_user b ON b.user_id = a.chat_sender_id
        WHERE 
        (a.chat_sender_id = ? AND a.chat_receive_id = ?) 
        OR (a.chat_sender_id = ? AND a.chat_receive_id = ?);
        ";

        $this->db->query($sql,[$contactID,$myID,$myID,$contactID]);
        
        if($this->db->num_rows() > 0)
        {
            while($x = $this->db->result_array())
            {
                $result = $x;
            }
        } 

        return $result;
    }

    /**
     * To display profile photos and chat friends icon status
     * 
     * @return array $result 
     */
    public function _profileFriends()
    {
        $result = [];

        $this->db->reset_select();
        $this->db->select('
        a.user_id as id,
        a.user_full_name as fullname,
        b.status_name as stts_online,
        a.user_photo as photo');
        $this->db->from('mst_user a');
        $this->db->join('ref_status_online b','b.status_code = a.user_status_online','inner');
        $this->db->where('a.user_id',Decrypt(Post()->id));
        $this->db->where('a.user_active',1);
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            $result = $this->db->result_array_one();
        }

        return $result;
    }   

    /**
     * Retrieve the most recent chat data
     * 
     * @param string $contactID  This comes from the id belonging to each contact list. Which comes from mst_user.user_id
     * @param string $myID This comes from my session id when logging in
     * @return array $result
     */
    public function _getDataLastChat($contactID,$myID)
    {
        $result = [];

        $sql = "SELECT 
        a.chat_id,
        a.chat_sender_id as sender_id,
        a.chat_receive_id as receive_id,
        a.chat_content as content,
        a.chat_read as chat_read,
        a.chat_type,
        a.chat_created_at as chat_date,
        b.user_photo as photo,
        b.user_status_online AS stts_online
        FROM {$this->table} 
        INNER JOIN mst_user b ON b.user_id = a.chat_sender_id
        WHERE 
        (a.chat_sender_id = ? AND a.chat_receive_id = ?) 
        OR (a.chat_sender_id = ? AND a.chat_receive_id = ?)
        ORDER BY a.chat_id DESC 
        LIMIT 1
        ";


        $this->db->query($sql,[$contactID,$myID,$myID,$contactID]);

        if($this->db->num_rows() > 0)
        {
                $result = $this->result_array_one();
        }
        return $result;
    }

    /**
     * Retrieve user data that is typing in real time
     * 
     * @param string $contactID  This comes from the id belonging to each contact list. Which comes from mst_user.user_id
     * @param string $myID This comes from my session id when logging in
     * @return int $count
     */
    public function _getDataTyping($contactID,$myID)
    {
        $count = 0;

        $sql = "SELECT COUNT(*) as total FROM mst_typing a WHERE a.typing_receive_id = ? AND a.typing_sender_id = ?";
        $this->db->query($sql,[$myID,$contactID]);
        
        if($this->db->row()->total > 0)
        {
            $count++;
        }

        return $count;
    }

    /**
     * If there is a new incoming message, 
     * give a notification in the form of a number symbol
     * 
     * @param string $contactID  This comes from the id belonging to each contact list. Which comes from mst_user.user_id
     * @param string $myID This comes from my session id when logging in
     * @return int $count
     */
    public function _getDataNewMessage($contactID,$myID)
    {
        $count = 0;

        $sql = "SELECT
        COUNT(*) as total
        FROM {$this->table}
        WHERE a.chat_sender_id = ? 
        AND a.chat_receive_id = ?
        AND a.chat_read = ?";

        $this->db->query($sql,[$contactID,$myID,0]);

        if($this->db->num_rows() > 0)
        {
            $count = $this->db->row()->total;
        }

        return $count;
    }

    /**
     * If the message has been read, 
     * it is a sign that the message has been read
     * 
     */
    public function _ChatRead($contactID,$myID)
    {
        $this->db->set('chat_read',1);
        $this->db->where('chat_sender_id',$contactID);
        $this->db->where('chat_receive_id',$myID);
        $this->db->update('mst_chat');
    }

    /**
     * Retrieve data based on search
     * 
     */
    public function _getDataBySearch()
    {
        $result = ['status' => false,'data' => null];
        $this->db->reset_select();
        $this->db->select('
        b.user_id as id,
        b.user_full_name as fullname,
        b.user_name as username,
        b.user_photo as photo,
        c.status_name as stts_online,
        a.chat_content as content,
        a.chat_type,
        a.chat_read,
        a.chat_sender_id as sender_id,
        a.chat_receive_id as receive_id,
        a.chat_created_at as chat_date
        ');
        $this->db->from('mst_chat a');
        $this->db->join('mst_user b',' (a.chat_sender_id = b.user_id) OR (a.chat_receive_id = b.user_id) ','inner');
        $this->db->join('ref_status_online c','b.user_status_online = c.status_code','inner');
        $this->db->where('b.user_id !=',userdata('id'));
        $this->db->like('a.chat_content',base64_decode(Post()->search));
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            foreach($this->db->result_array() as $rows)
            {
                $data[] = $rows;
            }

            $result = ['status' => true,'data' => $data];
        }

        return $result;
    }

    /**
     * When the button is entered, the chat message process
     * 
     * @param string $chat_type
     * @return int $count
     */
    public function _sendChat($chat_type)
    {

        $count = 0;
        $this->db->set('chat_sender_id',userdata('id'));
        $this->db->set('chat_receive_id',Decrypt(Post()->contactID));
        $this->db->set('chat_content',base64_decode(Post()->typing));
        $this->db->set('chat_read',0);
        $this->db->set('chat_type',$chat_type);
        $this->db->set('chat_created_at',date('Y-m-d H:i:s'));
        $this->db->insert('mst_chat');
        
        if($this->db->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }

    /**
     * Restore last chat data
     * 
     * @return array $result
     */
    public function _lastShowChat()
    {
        $result = [];

        $sql = "SELECT 
        a.chat_sender_id as sender_id,
        b.user_photo as photo,
        a.chat_created_at as chat_date,
        a.chat_content as content,
        b.user_status_online as stts_online,
        a.chat_read as chat_read,
        a.chat_receive_id as receive_id
        FROM mst_chat a 
        INNER JOIN mst_user b ON b.user_id = a.chat_sender_id
        WHERE 
        a.chat_sender_id = ? 
        AND a.chat_receive_id = ? 
        ORDER BY a.chat_id DESC LIMIT 1";
        
        $contactID = Decrypt(Post()->contactID);

        /**
         * This comes from my session id when logging in
         * 
         * @var string $id
         */
        $myID = userdata('id');

        $this->db->query($sql,[$myID,$contactID]);

        if($this->db->num_rows() > 0)
        {
            $result[] = $this->db->result_array_one();
        }

        return $result;
    }
    
    /**
     * Check if someone has typed
     * 
     * @return int $count
     */
    public function _checkTyping()
    {
        $count = 0;

        $this->db->reset_select();
        $this->db->select('count(*) as count');
        $this->db->from('mst_typing');
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            $count = $this->db->result_array_one()['count'];
        }
        
        return $count;
    }

    /**
     * Entering data while typing in a table
     * 
     * @return void 
     */
    public function _statusTyping()
    {
        $this->db->set('typing_sender_id',userdata('id'));
        $this->db->set('typing_receive_id',Decrypt(Post()->friendsID));
        $this->db->insert('mst_typing');
    }

    
    /**
     * When you are no longer typing then delete the words 'is typing'
     * 
     * @return void
     */
    public function _deleteTyping()
    {
        $this->db->reset_select();
        $this->db->where('typing_sender_id',userdata('id'));
        $this->db->delete('mst_typing');
    }

    /**
     * Send a photo in the message sent by the user
     * 
     * @return string $filename
     */
    public function _uploadImage($filename)
    {
        $count = 0;

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
        $friendsID = Decrypt(Post()->friendsID);

        $this->db->set('chat_sender_id',$myID);
        $this->db->set('chat_receive_id',$friendsID);
        $this->db->set('chat_content',$filename);
        $this->db->set('chat_read',0);
        $this->db->set('chat_type','images');
        $this->db->set('chat_created_at',date('Y-m-d H:i:s'));
        $this->db->insert('mst_chat');

        if($this->db->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }

    /**
     * Send a photo in the message sent by the user
     * 
     * @return string $filename
     */
    public function _uploadFile($filename)
    {
        $count = 0;

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
        $friendsID = Decrypt(Post()->friendsID);

        $this->db->set('chat_sender_id',$myID);
        $this->db->set('chat_receive_id',$friendsID);
        $this->db->set('chat_content',$filename);
        $this->db->set('chat_read',0);
        $this->db->set('chat_type','files');
        $this->db->set('chat_created_at',date('Y-m-d H:i:s'));
        $this->db->insert('mst_chat');

        if($this->db->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }
}