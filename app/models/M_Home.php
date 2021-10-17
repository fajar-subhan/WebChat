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
    public function getDataListContact()
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
}