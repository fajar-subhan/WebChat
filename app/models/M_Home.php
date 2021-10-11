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
}