<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produto_model extends CI_Model
{
    
    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'produto';
    
    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'produto.id';
    
    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL, $options = array()) {
        $this->db->select('*');
        $this->db->from(self::TABLE_NAME);
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field=>$value) {
                    $this->db->where($field, $value);
                }
            } else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }
        if(isset($options["pagination"])) {
            $this->db->limit($options["pagination"]["per_page"], $options["pagination"]["offset"]);
        }
        if(isset($options["like"])) {
            $this->db->or_like($options["like"]);
        }
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }
}
