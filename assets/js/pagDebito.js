
/**
 * Desenvolvido por: Clayton Eduardo Mergulhão - <contato@ismweb.com.br>
 * Categoria: Integração com API PagSeguro Transparente
 * Conteudo do Curso Pagseguro Transparente com PHP e Jquery JavaScript 
 * http://cursos.ismweb.com.br/cursos/info/pagseguro-transparente-com-php-e-jquery-javascript
 * Ano: 2016
 */ 

$(document).ready(function(){		

	var btnPagarDebito 	= $('#pag_debito');
	var msgDebito		= $('#msg_debito');
	var totalCompra		= $('#total_compra').val();	

	btnPagarDebito.on('click', function(event){
		event.preventDefault();

		var hashPagamento = PagSeguroDirectPayment.getSenderHash();

		$.ajax({         
	        url: 'core/ajax/debitoOnline.php',
	        type: 'POST',
	        data: {pag_hash: hashPagamento, pag_valor: totalCompra},
	        beforeSend: function(){
	        	msgDebito.html('<div class="alert alert-success" role="alert">Aguarde um instante</div>');        
	        },
	        success: function(data){
	        	btnPagarDebito.hide();
	        	msgDebito.html('<div class="alert alert-success" role="alert">Seus dados foram enviado para o PagSeguro<br>agora você precisa realizar a transferência on-line,<br><strong>Clique no botão pagar</strong><br /> <a href="'+data+'" target="_blanck" class="btn btn-success" title="Ambiente seguro">Abrir ambiente SEGURO</a></div>');	           		       
	        }               
    	});	

	})

});