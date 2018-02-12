<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pedido extends CI_Controller {

    private $data = array("content" => "",
        "footer_content" => array("javascripts" => array("assets/js/accounting.min")),
    );

    public function __construct() {
        parent::__construct();
        $this->load->model("Pedido_model", "Pedido", true);
        $this->load->model("Itens_Pedido_model", "Itens_Pedido", true);
        $this->load->model("Produto_model", "Produto", true);
    }

    public function index() {
        $p = $this->input->get('p') ? ($this->input->get('p') * 10 - 10) : 0;
        $s = $this->input->get('s') ? $this->input->get('s') : "";

        $options["like"] = array(
            "pedido.id" => $s
        );

        $options["pagination"] = array("offset" => $p, "per_page" => 10);

        $pedido = $this->Pedido->get(null, $options, true);

        if (!$pedido)
            show_message_alert("Não a registro para sua pesquisa", "warning");

        $count = count($this->Pedido->get(null, array("like" => array("pedido.id" => $s))));
        $url = site_url('cadastro/pedido');
        $links = create_pagination($url, $count);

        $this->data["content"]["data"] = $pedido;
        $this->data["content"]["links"] = $links;
        $this->data["content"]["data_options"]["s"] = $s;
        load_content("cadastro/pedido/index", $this->data);
    }

    public function checkout($id = ""){
        if ($pedido = $this->Pedido->get($id)) {
            $pedido = array_shift($pedido);
            $this->data["content"]["data"] = $pedido;
            load_content("checkout", $this->data);
        } 
    }
}

/* End of file Conhecimento.php */
/* Location: ./application/controllers/cadastro/Conhecimento.php */
