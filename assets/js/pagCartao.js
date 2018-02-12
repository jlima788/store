/**
 * Desenvolvido por: Clayton Eduardo Mergulhão - <contato@ismweb.com.br>
 * Categoria: Integração com API PagSeguro Transparente
 * Conteudo do Curso Pagseguro Transparente com PHP e Jquery JavaScript 
 * http://cursos.ismweb.com.br/cursos/info/pagseguro-transparente-com-php-e-jquery-javascript
 * Ano: 2016
 */ 

$(document).ready(function(){		
	var ccCampo			= $('#cc_numero');
	var btnPagarCartao 	= $('#pag_cartao');
	var errosCartao		= $('#erros_cartao');
	var totalCompra		= $('#total_compra').val();

	//FUNÇÃO PEGA BANDEIR DO CARÃO E PEGA OS PARCELAMENTO
	ccCampo.on('blur', function(event){
		event.preventDefault();
		var ccNumero 	= $('#cc_numero').val();
		var showBrand	= $('#show_brand');
		var ccErro		= $('#cc_erro');
		var ccBrand		= $('#cc_brand');
		var ccParcelas	= $('#cc_parcela');

		if (ccNumero >= 6) {	
			var cBin = ccNumero.substr(0, 6);
			PagSeguroDirectPayment.getBrand({
				cardBin: cBin,
				success: function(response){
					var brand = response.brand.name;
					showBrand.html('<img src="https://stc.pagseguro.uol.com.br/public/img/payment-methods-flags/68x30/'+brand+'.png">');
					ccBrand.val(brand);

					PagSeguroDirectPayment.getInstallments({
						amount: totalCompra,
						brand: brand,
						success: function(response){
							var rParcelas = response.installments[brand];
							ccErro.empty();
							errosCartao.empty();

							var numeroParcelas = '';
							var juros = '';

							$.each(rParcelas, function(key,value){
								juros = (value.interestFree == true) ? 'sem juros' : 'com juros';
								numeroParcelas += '<option value="'+value.quantity+'">'+value.quantity+' x de '+value.installmentAmount+'</option>';
							});

							ccParcelas.html(numeroParcelas);
						},
						error: function(response){
							ccErro.html('Cartão inválido');
						}
					});
				},
				error: function(response){
					ccErro.html('Cartão inválido');
				}
			});
		} else {
			ccErro.html('Cartão inválido');
		}
	})

	//FUNÇAO PARA CRIA TOKEN
	btnPagarCartao.on('click', function(event){
		event.preventDefault();
		var ccNumero 	= $('#cc_numero').val();
		var ccNome 		= $('#cc_nome').val();
		var ccMes 		= $('#cc_mes').val();
		var ccAno 		= $('#cc_ano').val();	
		var ccParcela 	= $('#cc_parcela').val();
		var ccBrand 	= $('#cc_brand').val();
		var ccCvv 		= $('#cc_cvv').val();

		PagSeguroDirectPayment.createCardToken({
			cardNumber: ccNumero,
			brand: ccBrand,
			cvv: ccCvv,
			expirationMonth: ccMes,
			expirationYear: ccAno,
			success: function(response){
				var token = response.card.token;
				errosCartao.empty();
				pagarCartao(token);
			},
			error: function(response){
				errosCartao.html(response);
			}
		});

	});

	//FUNÇAO PAR COM CARTÃO ENVIA OS DADOS VIA AJAX POST
	function pagarCartao(token){
		var hashPagamento  = PagSeguroDirectPayment.getSenderHash();
		var msgCartao 	   = $('#msg_cartao');
		var formPagamento  = $('#form_pagamento');
		var tokenPagamento = token; 
		var BASE_URL = "http://localhost/testestore/";

		var nome = $('#senderName');

		$.ajax({
			url:  BASE_URL+'ajax/cartao',
			type: 'POST',
			dataType: 'json',
			data: {pag_hash: hashPagamento, pag_valor: totalCompra, pag_token: tokenPagamento, nome: senderName},
			beforeSend: function(){
	        	 msgCartao.html('<div class="alert alert-success" role="alert">Dados de pagamento enviado, aguarde por favor.</div>');      
	        },
	        success: function(data){
	        	btnPagarCartao.hide();
	        	formPagamento.hide();
	        	var status = data.statusTransacao;
	        	var code = data.codeTransacao;
	        	msgCartao.html('<div class="alert alert-success text-center" role="alert"><h3>Pagamento realizado com sucesso.</h3> <br> Status do pagamento: <strong>'+ status +'</strong> <br> Código da transação: <strong>'+ code +'</strong></div>');
	        }          
		})
	}

});