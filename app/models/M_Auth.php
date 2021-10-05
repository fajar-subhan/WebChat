<?php 
namespace app\models;

use app\core\Model;

class M_Auth extends Model
{
    /**
     * Primary table name
     * 
     * @var string $table 
     */
    private $table  = "mst_user a";

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
     * Check if the username is already used by another user
     * 
     * @return int $count
     */
    public function _checkUsername()
    {
        $count = 0;

        $this->db->select('count(*) as count');
        $this->db->from($this->table);
        $this->db->where('a.user_name',Post()->username);
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            foreach($this->db->result_array() as $rows)
            {
                if($rows['count'] > 0)
                {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Save new user account data
     * 
     * @return int $count
     */
    public function _addAccount()
    {
        $count = 0;

        $this->db->set('user_full_name',Post()->fullname);
        $this->db->set('user_name',Post()->username);
        $this->db->set('user_password',password_hash(Post()->password,PASSWORD_DEFAULT));
        $this->db->set('user_active',1);
        $this->db->set('user_order',$this->_getLastQuery());
        $this->db->set('user_created_at',date('Y-m-d H:i:s'));
        $this->db->insert('mst_user');

        if($this->db->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }
    
    /**
     * Function to forget password, 
     * in order to update password
     * 
     * @return int $count
     */
    public function _updatePassword()
    {
        $count = 0;

        $this->db->set('user_updated_at',date('Y-m-d H:i:s'));
        $this->db->set('user_password',password_hash(Post()->password,PASSWORD_DEFAULT));
        $this->db->where('user_name',Post()->username);
        $this->db->update('mst_user');

        if($this->num_rows() > 0)
        {
            $count++;
        }

        return $count;
    }

    /**
     * Take the last value from the user_order column
     * 
     * @return int $order
     */
    public function _getLastQuery()
    {
        $order = 0;
        
        $this->db->select('a.user_order');
        $this->db->from('mst_user a');
        $this->db->order_by('a.user_order','DESC');
        $this->db->limit('1');
        $this->db->get();

        if($this->db->num_rows() > 0)
        {
            $order = $this->result_array()[0]['user_order'] + 1;
        }

        return $order;
    }
}