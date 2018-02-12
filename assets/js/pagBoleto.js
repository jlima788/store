
/**
 * Desenvolvido por: Clayton Eduardo Mergulhão - <contato@ismweb.com.br>
 * Categoria: Integração com API PagSeguro Transparente
 * Conteudo do Curso Pagseguro Transparente com PHP e Jquery JavaScript 
 * http://cursos.ismweb.com.br/cursos/info/pagseguro-transparente-com-php-e-jquery-javascript
 * Ano: 2016
 */ 

$(document).ready(function(){		
	var btnPagarBoleto 	= $('#pag_boleto');
	var msgBoleto 		= $('#msg_boleto');
	var valor_total		= $('#valor_total').val();
	var id_pedido       = $('#id_pedido').val();
	
	var BASE_URL        = "http://localhost/testestore/";

	//ENVIA SOLICITAÇAO DE PAGAMENTO POR BOLETO
	btnPagarBoleto.on('click', function(event){
		event.preventDefault();
		var hashPagamento = PagSeguroDirectPayment.getSenderHash();
		var senderEmail		= $('#senderEmail').val();
		var senderName		= $('#senderName').val();
		var senderCPF		= $('#senderCPF').val();
		var senderPhone		= $('#senderPhone').val();
		$.ajax({         
	        url: BASE_URL+'ajax/boleto',
	        type: 'POST',
	        data: {id_pedido: id_pedido, pag_hash: hashPagamento, valor_total: valor_total, senderEmail: senderEmail, 
	        	senderName: senderName, senderCPF: senderCPF, senderPhone: senderPhone},
	        beforeSend: function(){
	        	msgBoleto.html('<div class="alert alert-success" role="alert">Aguarde o boleto está sendo gerado.</div>');        
	        },
	        success: function(data){
	        	btnPagarBoleto.hide();
	        	msgBoleto.html('<div class="alert alert-success" role="alert">Boleto gerado com sucesso, <strong>clique no botão para imprimir</strong>.<br /> <a href="'+data+'" target="_blank" class="btn btn-success" title="Imprimir boleto">Imprimir Boleto</a></div>');	           		       
	        }               
	   	});	
	})
});