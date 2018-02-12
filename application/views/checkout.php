<main class="ls-main ">
    <div class="container-fluid">
        <h1 class="ls-title-intro">Checkout Transparente</h1>
   		<?php $itens_pedido = $this->Itens_Pedido->get(array("id_pedido"=>$data->id)); ?>
		<section class="tabs-pagamentos">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div>
					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs" role="tablist">
					  	<li role="presentation" class="active">
					  		<a href="#comprador" aria-controls="comprador" role="tab" data-toggle="tab">Dados Comprador</a>
					  	</li>
					    <!-- <li role="presentation">
					    	<a href="#cartao" aria-controls="cartao" role="tab" data-toggle="tab">Cartão de Crédito</a>
					    </li> -->
					    <li role="presentation">
					    	<a href="#boleto" aria-controls="boleto" role="tab" data-toggle="tab">Boleto Bancário</a>
					    </li>
					    <!-- <li role="presentation">
					    	<a href="#debito" aria-controls="debito" role="tab" data-toggle="tab">Débito Online</a>
					    </li> -->
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
				  	    <div role="tabpanel" class="tab-pane active" id="comprador">

				  	    	<div class="row">
				  	    		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
				  	    			<h3>Dados do comprador</h3>
				  			    	<p>
				  			    		Preencha o formulário abaixo, com os dados do comprador.
				  			    	</p>
				  	    		</div>
				  	    		<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
				  	    			<div class="cc_brand" id="show_brand"><!-- vai fica a bandeira do cartão --></div>
				  	    		</div>
				  	    	</div>	

				  	    	<div id="erros_cartao"><!-- aqui fica os erros --></div>
				  	    	<div id="msg_cartao"><!-- msg cartão --></div>

				  	    	<form action="" method="post" id="form_pagamento" name="form_pagamento">
				  	    		<div class="row">
				  	    			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
				  		    			<div class="form-group">
				  		    				<label>E-mail</label>
				  		    				<input type="text" id="senderEmail" class="form-control">
				  		    			</div>
				  		    		</div>
				  		    		<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
				  		    			<div class="form-group">
				  		    				<label>Nome completo</label>
				  		    				<input type="text" id="senderName" class="form-control">
				  		    			</div>
				  		    		</div>
				  	    		</div>

				  	    		<div class="row">
				  	    			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
				  		    			<div class="form-group">
				  		    				<label>CPF (somente números)</label>
				  		    				<input type="text" id="senderCPF" class="form-control">
				  		    			</div>
				  		    		</div>
				  		    		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
				  		    			<div class="form-group">
				  		    				<label>Telefone</label>
				  		    				<input type="text" id="senderPhone" class="form-control">
				  		    			</div>
				  		    		</div>
				  	    		</div>
				  	    		<input type="hidden" id="id_pedido" value="<?php echo $itens_pedido[0]->id_pedido ?>" >
				  	    		<input type="hidden" id="valor_total" value="<?php echo $itens_pedido[0]->valor_total ?>" />
		<!-- 		  	    	</form> -->
				  	    </div>
					    <div role="tabpanel" class="tab-pane" id="cartao">

					    	<div class="row">
					    		<div class="col-xs-12 col-sm-11 col-md-11 col-lg-11">
					    			<h3>Pagar com cartão de crédito</h3>
							    	<p>
							    		Preencha o formulário abaixo, com os dados do seu cartão.
							    	</p>
					    		</div>
					    		<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
					    			<div class="cc_brand" id="show_brand"><!-- vai fica a bandeira do cartão --></div>
					    		</div>
					    	</div>	

					    	<div id="erros_cartao"><!-- aqui fica os erros --></div>
					    	<div id="msg_cartao"><!-- msg cartão --></div>


					    	<!-- <form action="" method="post" id="form_pagamento" name="form_pagamento"> -->
					    		<div class="row">
					    			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
						    			<div class="form-group">
						    				<label>Número do cartão</label>
						    				<input type="text" id="cc_numero" class="form-control">
						    				<span id="cc_erro" class="erro"><!-- erro no cartão --></span>
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
						    			<div class="form-group">
						    				<label>Nome no cartão</label>
						    				<input type="text" id="cc_nome" class="form-control">
						    			</div>
						    		</div>
					    		</div>

					    		<div class="row">
					    			<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
						    			<div class="form-group">
						    				<label>Válidade mës</label>
						    				<input type="text" id="cc_mes" class="form-control">
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
						    			<div class="form-group">
						    				<label>Válidade ano</label>
						    				<input type="text" id="cc_ano" class="form-control">
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
						    			<div class="form-group">
						    				<label>CVV</label>
						    				<input type="text" id="cc_cvv" class="form-control">
						    			</div>
						    		</div>
						    		<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
						    			<div class="form-group">
						    				<label>Parcelamento</label>
						    				<select id="cc_parcela" class="form-control">
						    					<option value=""></option>
						    				</select>
						    			</div>
						    		</div>
					    		</div>

					    		<div class="row">
					    			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					    				<button id="pag_cartao" class="btn btn-primary">Pagar com cartão</button>
						    		</div>
						    	</div>
						    	<input type="hidden" id="cc_brand" value="">
						    	<input type="hidden" id="total_compra" value="100.00">
					    	</form>


					    	<h4 class="text-center margin-top20">Aceitamos os seguintes cartões</h4>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<ul class="PaymentMethods" id="PaymentMethods">
										<!-- Aqui vai carregar as bandeiras de pagamento -->

									</ul>
								</div>
							</div>
					    </div>
					    <div role="tabpanel" class="tab-pane" id="boleto">
					    	<h3>Pagar com boleto bancário</h3>
					    	<p>
					    		Para pagar com boleto bancário, clique no botão abaixo e aguarde a geração do boleto.
					    	</p>  
					    	<p>
					    		<a href="" title="Pagar com boleto bancário" id="pag_boleto" class="ls-btn-primary">
					    			Pagar com boleto
					    		</a>
					    	</p> 

					    	<div id="msg_boleto"></div> 	
					    </div>
					    <div role="tabpanel" class="tab-pane" id="debito">
					    	<h3>Pagar com cartão de crédito</h3>
					    	<p>
					    		Para pagar com débito em conta, clieque no botão abaixo.
					    	</p> 					    	
					    	<p>
					    		<a href="" title="Pagar com débito online" id="pag_debito" class="btn btn-primary">
					    			Pagar com débito online
					    		</a>
					    	</p> 
					    	<div id="msg_debito"></div>  	
					    </div>				  
					  </div>

					</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</main>

<!-- Arquivos JS -->
<script src="<?php echo site_url('assets/dist/jquery/jquery.js'); ?>"></script>
<script src="<?php echo site_url('assets/dist/bootstrap/js/bootstrap.min.js'); ?>"></script>
<script src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script src="<?php echo site_url('assets/js/getPaymentMethods.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/pagBoleto.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/pagDebito.js'); ?>"></script>
<script src="<?php echo site_url('assets/js/pagCartao.js'); ?>"></script>