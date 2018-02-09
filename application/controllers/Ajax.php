<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax extends CI_Controller {


	public function getSessionId(){

	$params['email'] = "jlima788@gmail.com";
	$params['token'] = "04ABFC1D3B5B444A8931185DD96BBF80";
	$url_checkout_sandbox = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions';

	$xml = "";
	$ch = curl_init($url_checkout_sandbox);

	curl_setopt_array(
		$ch, 
		array(
		CURLOPT_POSTFIELDS     => http_build_query($params),
		      CURLOPT_POST           => count($params),
		      CURLOPT_RETURNTRANSFER => 1,
		      CURLOPT_TIMEOUT        => 45,
		      CURLOPT_SSL_VERIFYPEER => false,
		      CURLOPT_SSL_VERIFYHOST => false,
		)
	);
	
	$response = NULL;
	try{
	      $response = curl_exec($ch);
	    }catch(Exception $e){
	      Mage::logException($e);
	      return false;
	    }

	    libxml_use_internal_errors(true);
	    $xml = simplexml_load_string($response);
	    if(false === $xml){
	          if(curl_errno($ch) > 0){
	            $this->writeLog("Falha de comunicação com API do Pagseguro: ".curl_error($ch));
	          }else{
	            echo "Falha na autenticação com Pagseguro".'<br/>'.
	            "Retorno: ".$response;
	          }
	          return false;
	        }
	        echo '{"id":"'.$xml->id.'"}';
	}
}
