$(document).ready(function(){

	var BASE_URL = "http://localhost/testestore/";
	$.ajax({
	    url: BASE_URL+'ajax/getSessionId',
	    dataType: 'json',
	    success: function(data){
	        var idSessao = data.id;
	        PagSeguroDirectPayment.setSessionId(idSessao);

	        PagSeguroDirectPayment.getPaymentMethods({
	          success: function(response){
	  		           var PaymentMethods = '';
	  		           $.each(response.paymentMethods.CREDIT_CARD.options, function(key,value){
	  		                PaymentMethods+= '<li class="icone"><img src=https://stc.pagseguro.uol.com.br'+value.images.SMALL.path+' /></li>';
	  		           });
	            $('#paymentMethods').html(PaymentMethods);
	          }
	      });
	    }
	  });

	//Pagamento Cartao de Credito
	  var ccCampo = $('#cc_numero');
	  var btnPagar = $('#btn_Pagar');
	  var totalCompra = $('#total').val();
	  var errorCartao = $('#error_card');

	  ccCampo.on('blur', function(event){
	    event.preventDefault();

	    var ccNumero = $('#cc_numero').val();
	    var showBrand = $('#show_brand');
	    var ccErro = $('#cc_error');
	    var ccBrand = $('#cc_brand');
	    var ccParcelas = $('#cc_parcelas');


	    //Identificar a marca do cartao
	    if(ccNumero >= 6){
	      var cBin = ccNumero.substr(0, 6);
	      PagSeguroDirectPayment.getBrand({
	        cardBin: cBin,
	        success: function(response){
	          var brand = response.brand.name;
	          showBrand.html('<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/42x20/'+brand+'.png">');
	          ccBrand.val(brand);
	          //Parcelamento
	          PagSeguroDirectPayment.getInstallments({
	            amount : totalCompra,
	            brand: brand,
	            success: function(response){
	              var rParcelas = response.installments[brand];
	              ccErro.empty();
	              errorCartao.empty();

	              var numeroParc = "10";
	              var juros = "";

	              $.each(rParcelas, function(key, value){
	                juros = (value.interestFree == true) ? 'sem juros' : 'com juros';
	                numeroParc += '<option value="'+value.quantity+'">'+value.quantity+' x de '+value.installmentAmount+" "+juros+'</option>';
	              });
	              ccParcelas.html(numeroParc);
	            },
	            error: function(response){
	              ccErro.html("Cartão inválido");
	            }
	          });
	        },
	        error: function(response){
	          ccErro.html("Cartão inválido");
	        }
	      });
	    }else{
	      ccErro.html('Cartão inválido');
	    }
	  })
});


//rola trazer os campos para o centro?.

