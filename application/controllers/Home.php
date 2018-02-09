<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    private $data = array("content" => "",
        "footer_content" => array("javascripts" => array()),
    );

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        load_content("home", $this->data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */