<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Itens_Pedido_model extends CI_Model
{
    
    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'pedido_itens';
    
    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'pedido_itens.id_pedido';
    
    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL, $options = array(), $flag = false) {
        $this->db->select('pedido_itens.*, produto.nome');
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
        $this->db->join('produto', 'produto.id = pedido_itens.id_produto_pai');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }
}
