<?php

class Hooks {

	public $CI;

	public function __construct() {
		$this->CI =& get_instance();
	}

	public function log() {
		$controller = $this->CI->router->fetch_class();
		$function =  $this->CI->router->fetch_method();
		$url = site_url(get_class_url());
		$url_default = site_url();
		$usuario = $this->CI->session->userdata('usuario');
		if ($function == "update" || $function == "insert" || $function == "remover") {
			$id = $this->CI->uri->segment(count($this->CI->uri->segment_array()));
			$segment = $function == "update" ? "editar" : "";
			$url .= "/$segment/$id";
			$description = array(
				"update" => "<b><a href='$url_default/cadastro/usuario/editar/$usuario->usucod'>$usuario->usunome</a></b> Atualizou o registro <b><a href='$url'>#$id</a></b> na tela de <b>$controller</b>",
				"insert" => "<b><a href='$url_default/cadastro/usuario/editar/$usuario->usucod'>$usuario->usunome</a></b> inserio um registro na tela de <b>$controller</b>",
				"remover" => "<b><a href='$url_default/cadastro/usuario/editar/$usuario->usucod'>$usuario->usunome</a></b> removeu um registro na tela de <b>$controller</b>"
			);
			$this->CI->Log->insert(array(
					"controller" => $controller,
					"function" => $function,
					"descricao" => $description[$function],
					"id_usuario" => $usuario->usucod
				)
			);
		}
	}
	
	// public function is_auth() {
	// 	$auth = $this->CI->session->userdata('logged') ? true : false;
	// 	if (!$auth) {
	// 		$controller = $this->CI->router->fetch_class();
	// 		if ($controller != "login") {
	// 			redirect('login');
	// 		}
	// 	}
	// }
}