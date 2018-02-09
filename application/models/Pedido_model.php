<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pedido_model extends CI_Model
{
    
    /**
     * @name string TABLE_NAME Holds the name of the table in use by this model
     */
    const TABLE_NAME = 'pedido_itens';
    
    /**
     * @name string PRI_INDEX Holds the name of the tables' primary index used in this model
     */
    const PRI_INDEX = 'pedido_itens.id';
    
    /**
     * Retrieves record(s) from the database
     *
     * @param mixed $where Optional. Retrieves only the records matching given criteria, or all records if not given.
     *                      If associative array is given, it should fit field_name=>value pattern.
     *                      If string, value will be used to match against PRI_INDEX
     * @return mixed Single record if ID is given, or array of results
     */
    public function get($where = NULL, $options = array(), $flag = false) {
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
        if($flag)
            $this->db->group_by('id_pedido');
        $this->db->order_by('id', 'desc');
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } else {
            return array();
        }
    }

    public function get_servicos_conhecimento($where = NULL) {
        $this->db->select('tbl_itensctrc.*, tbl_servicos.descricao');
        $this->db->join('tbl_itensctrc', 'tbl_itensctrc.codservico = tbl_servicos.codigo','left');
        $this->db->from("tbl_servicos");
        if ($where !== NULL) {
            if (is_array($where)) {
                foreach ($where as $field => $value) {
                    $this->db->where($field, $value);
                }
            } 
            else {
                $this->db->where(self::PRI_INDEX, $where);
            }
        }

        $this->db->order_by('tbl_servicos.descricao', 'desc');
        $result = $this->db->get()->result();
        if ($result) {
            return $result;
        } 
        else {
            return false;
        }
    }

    public function get_xml_conhecimento($data = array()) {
        $query = "SELECT "
                       . " t1.id_solicitacao,"
                       . " t1.conhecimento_id,"
                       . " t1.cfop,"
                       . " (SELECT ibge_cidade FROM tbl_cidades t2 WHERE t2.codigo = t1.origem) as origem,"
                       . " (SELECT uf FROM tbl_cidades t2 WHERE t2.codigo = t1.origem) as uf_origem,"
                       . " (SELECT ibge_cidade FROM tbl_cidades t3 WHERE t3.codigo = t1.destino) as destino,"
                       . " (SELECT uf FROM tbl_cidades t3 WHERE t3.codigo = t1.destino) as uf_destino,"
                       . " t1.observacoes,"
                       . " t4.cpfcnpj as cnpj_rem,"
                       . " t4.ie as ie_rem,"
                       . " t4.razao_social as razao_rem,"
                       . " t4.nome_fantasia as fantasia_rem,"
                       . " t4.logradouro as logradouro_rem," 
                       . " t4.numero as numero_rem," 
                       . " t4.complemento as complemento_rem,"
                       . " t4.bairro as bairro_rem,"
                       . " t4.cidade as cidade_rem,"
                       . " t4.estado as estado_rem,"
                       . " t4.cep_empresa as cep_rem,"
                       . " t4.im as im_rem,"
                       . " t4.telefone1 as telefone_rem,"
                       . " t5.cpfcnpj as cnpj_dest,"
                       . " t5.ie as ie_dest,"
                       . " t5.razao_social as razao_dest,"
                       . " t5.nome_fantasia as fantansia_dest,"
                       . " t5.logradouro as logradouro_dest,"
                       . " t5.numero as numero_dest,"
                       . " t5.complemento as complemento_dest,"
                       . " t5.bairro as bairro_dest,"
                       . " t5.cidade as cidade_dest,"
                       . " t5.estado as estado_dest,"
                       . " t5.cep_empresa as cep_dest," 
                       . " t5.im as im_dest," 
                       . " t5.telefone1 as telefone_dest,"
                       . " t1.vlr_prestacao as prestacao,"
                       . " t1.vlr_frete_peso as frete_peso,"
                       . " t1.vlr_frete as frete,"
                       . " t1.vlr_despacho as despacho,"
                       . " t1.vlr_pedagio as pedagio,"
                       . " t1.vlr_icms_base_calculo as base,"
                       . " t1.vlr_icms_percentual as perc,"
                       . " t1.vlr_icms as icms,"
                       . " t1.vlr_mercadoria as valor_mercadoria,"
                       . " (SELECT descricao FROM tbl_mercadorias t6 WHERE t6.codigo = t1.mercadoria) as mercadoria,"
                       . " t1.peso_bruto as peso_bruto,"
                       . " t10.antt,"
                       . " (SELECT nome FROM tbl_motoristas t7 WHERE t7.codigo = t1.cod_motorista) as motorista,"
                       . " (SELECT cpf FROM tbl_motoristas t7 WHERE t7.codigo = t1.cod_motorista) as cpf,"
                       . " t1.previsao_entrega,"
                       . " t1.tipo_carregamento," 
                       . " t11.cpfcnpj as cnpj_exp,"
                       . " t11.ie as ie_exp,"
                       . " t11.razao_social as razao_exp,"
                       . " t11.logradouro as logradouro_exp," 
                       . " t11.numero as numero_exp,"
                       . " t11.complemento as complemento_exp,"
                       . " t11.bairro as bairro_exp,"
                       . " t11.cidade as cidade_exp,"
                       . " t11.estado as estado_exp,"
                       . " t11.cep_empresa cep_exp,"
                       . " t11.telefone1 as telefone_exp,"
                       . " t12.cpfcnpj as cnpj_rec,"
                       . " t12.ie as ie_rec,"
                       . " t12.razao_social as razao_rec,"
                       . " t12.logradouro as logradouro_rec,"
                       . " t12.numero as numero_rec,"
                       . " t12.complemento as complemento_rec,"
                       . " t12.bairro as bairro_rec,"
                       . " t12.cidade as cidade_rec,"
                       . " t12.estado as estado_rec,"
                       . " t12.cep_empresa as cep_rec," 
                       . " t12.telefone1 as telefone_rec,"
                       . " t1.tipo_tomador as tipo_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT cpfcnpj FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as cnpj_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT ie FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as ie_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT razao_social FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as razao_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT logradouro FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as logradouro_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT numero FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as numero_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT complemento FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as complemento_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT bairro FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as bairro_tomador,"
                       . " IF(t1.tipo_tomador = 5, t16.ibge_cidade, '') as cidade_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT estado FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as estado_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT cep_empresa FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as cep_tomador,"
                       . " IF(t1.tipo_tomador = 5,(SELECT telefone1 FROM tbl_empresas t8 WHERE t8.id_empresa = t1.tomador),'') as telefone_tomador,"
                       . " t1.local_coleta as loc_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT cpfcnpj FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as cnpj_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT ie FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as ie_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT razao_social FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as razao_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT logradouro FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as logradouro_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT numero FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as numero_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT complemento FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as complemento_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT bairro FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as bairro_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT cidade FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as cidade_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT estado FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as estado_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT cep_empresa FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as cep_local_coleta,"
                       . " IF(t1.local_coleta <> NULL,(SELECT telefone1 FROM tbl_empresas t13 WHERE t13.id_empresa = t1.local_coleta),'') as telefone_local_coleta,"
                       . " t1.local_entrega as loc_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT cpfcnpj FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as cnpj_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT ie FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as ie_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT razao_social FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as razao_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT logradouro FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as logradouro_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT numero FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as numero_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT complemento FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as complemento_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT bairro FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as bairro_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT cidade FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as cidade_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT estado FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as estado_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT cep_empresa FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as cep_local_entrega,"
                       . " IF(t1.local_entrega <> NULL,(SELECT telefone1 FROM tbl_empresas t14 WHERE t14.id_empresa = t1.local_entrega),'') as telefone_local_entrega,"
                       . " IF(t4.ds_pais <> NULL, (SELECT codigo FROM tbl_paises t17 WHERE t17.sigla = t4.ds_pais), '') as pais_rem,"
                       . " IF(t5.ds_pais <> NULL, (SELECT codigo FROM tbl_paises t20 WHERE t20.sigla = t5.ds_pais), '') as pais_des,"
                       . " (SELECT ibge_cidade FROM tbl_cidades t18 WHERE t4.cidade = t18.descricao) as ibge_cidade_rem,"
                       . " (SELECT ibge_cidade FROM tbl_cidades t19 WHERE t5.cidade = t19.descricao) as ibge_cidade_dest,"
                       . " (SELECT descricao FROM tbl_cidades t2 WHERE t2.codigo = t1.origem) as descr_cidade_rem,"
                       . " (SELECT descricao FROM tbl_cidades t3 WHERE t3.codigo = t1.destino) as descr_cidade_des"
                       . " FROM tbl_conhecimentos t1"
                       . " LEFT JOIN tbl_empresas t4"
                       . " ON t1.remetente = t4.id_empresa"
                       . " LEFT JOIN tbl_empresas t5"
                       . " ON t1.destinatario = t5.id_empresa"
                       . " LEFT JOIN tbl_veiculos t9"
                       . " ON t1.placa_veiculo = t9.placa"
                       . " LEFT JOIN tbl_motoristas t10"
                       . " ON t9.cod_proprietario = t10.codigo"
                       . " LEFT JOIN tbl_empresas t11"
                       . " ON t1.retirada = t11.id_empresa"
                       . " LEFT JOIN tbl_empresas t12"
                       . " ON t1.devolucao = t12.id_empresa"
                       . " LEFT JOIN tbl_empresas t15"
                       . " ON t1.tomador = t15.id_empresa"
                       . " LEFT JOIN tbl_cidades t16"
                       . " ON t15.cidade = t16.codigo"
                       . " WHERE t1.id_solicitacao = '" .$data["id_solicitacao"]. "' and t1.seq = '" . $data["seq"] . "'";
        $result = $this->db->query($query);
        return !$result->result() ? false : $result->result();
    }
    
    /**
     * Inserts new data into database
     *
     * @param Array $data Associative array with field_name=>value pattern to be inserted into database
     * @return mixed Inserted row ID, or false if error occured
     */
    public function insert($data) {
        if ($this->db->insert(self::TABLE_NAME, $data)) {
            return $this->db->insert_id();
        } 
        else {
            return false;
        }
    }

    public function insert_servicos_conhecimento($data) {
        if ($this->db->insert("tbl_itensctrc", $data)) {
            return $this->db->insert_id();
        } 
        else {
            return false;
        }
    }
    
    /**
     * Updates selected record in the database
     *
     * @param Array $data Associative array field_name=>value to be updated
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of affected rows by the update query
     */
    public function update($data, $where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->update(self::TABLE_NAME, $data, $where);
        return $this->db->affected_rows();
    }
    
    /**
     * Deletes specified record from the database
     *
     * @param Array $where Optional. Associative array field_name=>value, for where condition. If specified, $id is not used
     * @return int Number of rows affected by the delete query
     */
    public function delete($where = array()) {
        if (!is_array($where)) {
            $where = array(self::PRI_INDEX => $where);
        }
        $this->db->delete(self::TABLE_NAME, $where);
        return $this->db->affected_rows();
    }

    public function delete_conhecimento_servico($where = array()) {
        if (!is_array($where)) {
            $where = array("tbl_itensctrc.seq" => $where);
        }
        $this->db->delete("tbl_itensctrc", $where);
        return $this->db->affected_rows();
    }
}
