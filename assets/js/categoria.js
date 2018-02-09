(function(AdicionarCategoria, $){
	AdicionarCategoria.methods = AdicionarCategoria.methods || {
		_Main: function() {
			AdicionarCategoria.methods._click();
		},
		_click: function(){
			$("[name=removerCategoria]").on("click", function(){
				var id = $(this).attr("data-id");
				AdicionarCategoria.methods._excluirCategoria(id, this);
			});
			$("[name=verSubCategorias]").on("click", function(){
				var id_categoria = $(this).attr("data-id");
				var nome_categoria = $(this).attr("data-name");
				AdicionarCategoria.methods._subCategorias(id_categoria, nome_categoria);
			});
		},
		_subCategorias:function(id_categoria, nome_categoria) {
			var data = {id_categoria:id_categoria};
			var url = "financeiro/categoria/listar_sub_categorias";
			Main.methods._post(url, data).done(function(responseData){
				if(responseData.status){
					var data = responseData.data;
					var tbody = $("#listaSubCategoria").find("#subCategorias").empty();
					$("#listaSubCategoria").find("#CategoriaNome").text(nome_categoria);
					for(var i in data){
						tbody.append(
							$("<tr/>").append(
								$("<td/>").append('<a title="Editar" href="sub_categoria/editar/'+data[i].id+'"><i class="ls-ico-pencil">&nbsp;</i></a>',
												$("<a/>",{
													'title':'Editar',
													'href':'#',
													'data-id':data[i].id
												}).append('<i class="ls-ico-remove">&nbsp;</i>').on('click', function(){
													var id = $(this).attr("data-id");
													AdicionarCategoria.methods._excluirSubCategoria(id, this);
												})),
								$("<td/>").text(data[i].nome)
							)
						);
					}
					locastyle.modal.open("#listaSubCategoria");
				} else {
					alert("Não a sub-categorias");
				}
			});
		},
		_excluirSubCategoria: function(id, element){
			conf = confirm("Deseja realmente excluir?");
			if(conf){
				var url = "financeiro/sub_categoria/remover";
				var data = {id:id};
				Main.methods._post(url,data).done(function(responseData){
					if(responseData.status) {
						if(typeof element != "undefined"){
							$(element).parent().parent().remove();
						}
					} else {
						alert("error ao excluir");
					}
				});
			}
		},
		_excluirCategoria: function(id, element){
			conf = confirm("Deseja realmente excluir?");
			if(conf){
				var url = "financeiro/adicionar_categorias/excluir";
				var data = {id:id};
				Main.methods._post(url,data).done(function(responseData){
					if(responseData.status) {
						if(typeof element != "undefined"){
							$(element).parent().parent().remove();
						}
					} else {
						alert("error ao excluir");
					}
				});
			}
		}
	}
	AdicionarCategoria.methods._Main();
})(window.AdicionarCategoria = window.AdicionarCategoria || {}, $);