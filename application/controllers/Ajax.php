<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {

	public function __construct() {
	    parent::__construct();
	    $this->load->model("Itens_Pedido_model", "Itens_Pedido", true);
	    $this->load->model("Produto_model", "Produto", true);
	}

	private $ambiente   = 'sandbox';
	private $email 		= 'jlima788@gmail.com';
	private $token 		= '04ABFC1D3B5B444A8931185DD96BBF80';

	public function getSessionId(){

		//URL DA API
		$url = 'https://ws'.($this->ambiente == 'sandbox' ? '.sandbox' : '' ).'.pagseguro.uol.com.br/v2/sessions';

		//INICIA O CURL
		$ch = curl_init($url);

		//PARAMETROS
		$params['email'] = $this->email; 
		$params['token'] = $this->token;

		//EXECUTA O CURL
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_POSTFIELDS		=> http_build_query($params),
				CURLOPT_POST 			=> count($params),
				CURLOPT_RETURNTRANSFER 	=> 1,
				CURLOPT_TIMEOUT			=> 45,
				CURLOPT_SSL_VERIFYPEER 	=> false,
				CURLOPT_SSL_VERIFYHOST 	=> false, 
			)
		);

		$response = NULL;

		try{
			$response = curl_exec($ch);
		} catch (Exception $e){
			Mage::logException($e);
			return false;
		}

		libxml_use_internal_errors(true);

		$xml = simplexml_load_string($response);
		if (false === $xml) {
			if (curl_errno($ch) > 0) {
				$this->writeLog('Falha de comunicação com a API do PagSeguro: '. curl_error($ch));
			} else {
				$this->writeLog('Falha na autenticação com API do PagSeguro email e token cadastrados.
					Retorno: '. $response
				);
			}
			return false;
		}

		//RETORNA PARA O JSON
		echo '{"id":"'.$xml->id.'"}';

	}

	public function cartao(){

		//URL DA API
		$endereco = 'https://ws'.($this->ambiente == 'sandbox' ? '.sandbox' : '' ).'.pagseguro.uol.com.br/v2/transactions';

		/**
		 * MONTA A ARRAY() COM OS DADOS 
		 * ESSES DADOS PODEM SER PASSADOS VIA POST OU PEGAR DO BANCO DE DADOS
		 * VAI DEPENDER DE COMO SEU SISTEMA TRABALHA		 
		 */	    
		if (!empty($_POST)) {
			
		    $data = array(
			   	"email"			=> $this->email,
			   	"token"			=> $this->token,   
			   	"paymentMode"	=> "default",
				"paymentMethod"	=> "creditCard",
				"receiverEmail"	=> $this->email,
				
				"currency"		=>	"BRL",
				"extraAmount"	=>	"",
				
				"itemId1"		=> "0001",
				"itemDescription1" 	=> "Notebook Prata",
				"itemAmount1" 		=> "100.00",
				"itemQuantity1"		=>"1",
					
				"notificationURL"	=>	"",
				"reference"			=>	$_POST['id'],
				
				"senderName"		=> 	$_POST['senderName'],
				"senderCPF"			=>	$_POST['senderCPF'],
				"senderAreaCode"	=>	"43",
				"senderPhone"		=>	$_POST['senderPhone'],
				"senderEmail"		=>	"teste@sandbox.pagseguro.com.br",
				"creditCardToken" 	=>  $_POST['pag_hash'],
				"shippingAddressStreet"		=>	"Av das flores",
				"shippingAddressNumber"		=> "123",
				"shippingAddressComplement"	=> "Casa",
				"shippingAddressDistrict"	=> "Centro",
				"shippingAddressPostalCode"	=> "86220000",
				"shippingAddressCity"		=>	"Assai",
				"shippingAddressState"		=>	"PR", 
				"shippingAddressCountry"	=> "BRA", 
				
				"shippingType"		=> "1", 
				"shippingCost"		=> "0.00",	

				"creditCardToken" 	=> $_POST['pag_token'],

				"installmentQuantity"	=> "1",
				"installmentValue" 		=> "100.00",

				"creditCardHolderName"	=>	"clayton mergulhao",
				"creditCardHolderCPF"	=> 	"11475714734",
				"creditCardHolderBirthDate"	=>	"10/10/1980", 
				"creditCardHolderAreaCode"	=>	"43",
				"creditCardHolderPhone"	=>	"32624001",
				"billingAddressStreet"	=>	"Av Rio de Janeiro",	
				"billingAddressNumber"	=>	"1060",
				"billingAddressComplement"	=>	"Comercial",
				"billingAddressDistrict"	=>	"Centro",
				"billingAddressPostalCode"	=>	"86220000",
				"billingAddressCity"	=>	"Assai",
				"billingAddressState"	=>	"PR",
				"billingAddressCountry" => "BRA"
									
			);

		    //FAZ A MÁGICA QUE PEGA OS DADOS DA ARRAY() E MONTA A URL COMPLETA
			$fields_string = '';
		    foreach ($data as $key=>$value)
		    { 
		    	$fields_string .= $key.'='.$value.'&'; 
		    }
		    $fields_string = rtrim($fields_string,'&');

			$ch = curl_init();    
	    	curl_setopt($ch,CURLOPT_URL, $endereco);
	    	curl_setopt($ch,CURLOPT_POST, count($data));
	    	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	    	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);    		
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	   		curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 

			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		    //execute post
		    $result = curl_exec($ch);

		    //close connection
		    curl_close($ch);

		    //PEGA O RESULTADO E CONVERTE EM STRING
			$result = simplexml_load_string($result);
			
			//IMPRIMI O RETORNO EM JSON
			echo '{"codeTransacao":"'.$result->code.'", "statusTransacao":"'.$status.'"}';
		}
	}

	public function boleto(){
		if (!empty($_POST)) {

			//URL DA API
			$endereco = 'https://ws'.($this->ambiente == 'sandbox' ? '.sandbox' : '' ).'.pagseguro.uol.com.br/v2/transactions';

			$id = $_POST['id_pedido'];

			if ($itens_pedido = $this->Itens_Pedido->get($id)) {
			    
			} 
		    
			/**
			 * MONTA A ARRAY() COM OS DADOS 
			 * ESSES DADOS PODEM SER PASSADOS VIA POST OU PEGAR DO BANCO DE DADOS
			 * VAI DEPENDER DE COMO SEU SISTEMA TRABALHA		 
			 */

		    $data = array(
			   	"email"			=> $this->email,
			   	"token"			=> $this->token,   
			   	"paymentMode"	=> "default",
				"paymentMethod"	=> "boleto",
				"receiverEmail"	=> $this->email,
				
				"currency"		=>	"BRL",
				"extraAmount"	=>	"",
				
				"notificationURL"	=>	"",
				"reference"			=>	$_POST['id_pedido'],
				
				"senderName"		=> 	$_POST['senderName'],
				"senderCPF"			=>	$_POST['senderCPF'],
				"senderAreaCode"	=>	"13",
				"senderPhone"		=>	$_POST['senderPhone'],
				"senderEmail"		=>	$_POST['senderEmail'],			
				"shippingAddressStreet"		=>	"Av das flores",
				"shippingAddressNumber"		=> "123",
				"shippingAddressComplement"	=> "Casa",
				"shippingAddressDistrict"	=> "Centro",
				"shippingAddressPostalCode"	=> "86220000",
				"shippingAddressCity"		=>	"Assai",
				"shippingAddressState"		=>	"PR", 
				"shippingAddressCountry"	=> "BRA", 
				
				"shippingType"	=> "1", 
				"shippingCost"	=> "0.00",						
			);


			$i = 1;
			foreach ($itens_pedido as $value) {
			    $data["itemId$i"]		   = $i;
			    $data["itemDescription$i"] = $itens_pedido[$i-1]->nome;
			    $data["itemAmount$i"] 	   = $itens_pedido[$i-1]->valor_total;
			    $data["itemQuantity$i"]	   = $itens_pedido[$i-1]->quantidade;
				$i++;
			}

			//FAZ A MÁGICA QUE PEGA OS DADOS DA ARRAY() E MONTA A URL COMPLETA
			$fields_string = '';
		    foreach ($data as $key=>$value)
		    { 
		    	$fields_string .= $key.'='.$value.'&'; 
		    }
		    $fields_string = rtrim($fields_string,'&');

		    //INICIA O CURL
			$ch = curl_init();    
	    	curl_setopt($ch,CURLOPT_URL, $endereco);
	    	curl_setopt($ch,CURLOPT_POST, count($data));
	    	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	    	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);    		
	        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	   		curl_setopt($ch,  CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		    //PEGA O RETORNO DO CURL
		    $result = curl_exec($ch);

		    //FINALIZA O CURL
		    curl_close($ch);

		    //PEGA O RESULTADO E CONVERTE EM STRING
			$result = simplexml_load_string($result);
				
			//EXIBE NA TELA O LINK
			echo($result->paymentLink);				
		
		}
	}

}
