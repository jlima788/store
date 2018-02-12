/**
 * Desenvolvido por: Clayton Eduardo Mergulhão - <contato@ismweb.com.br>
 * Categoria: Integração com API PagSeguro Transparente
 * Conteudo do Curso Pagseguro Transparente com PHP e Jquery JavaScript 
 * http://cursos.ismweb.com.br/cursos/info/pagseguro-transparente-com-php-e-jquery-javascript
 * Ano: 2016
 */ 


$(document).ready(function(){
	var BASE_URL = "http://localhost/testestore/";
	$.ajax({         
        url: BASE_URL+'ajax/getSessionID',
        dataType: 'json',
        success: function(data){
            var idSessao = data.id;               
            PagSeguroDirectPayment.setSessionId(idSessao);
            PagSeguroDirectPayment.getPaymentMethods({
		        success: function(response){ 			        	
		           var PaymentMethods = '';			           
		           $.each(response.paymentMethods.CREDIT_CARD.options, function(key,value){			                
		                PaymentMethods+= '<li class="icone"><img src=https://stc.pagseguro.uol.com.br'+value.images.MEDIUM.path+' /></li>';   			                   
		           });
		           $('#PaymentMethods').html(PaymentMethods);
		        }
		    }); 			       
        }               
    });	
});