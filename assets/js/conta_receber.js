(function() {
    ContaReceber.methods = ContaReceber.methods || {
        _init: function() {
            ContaReceber.methods._change();
            ContaReceber.methods._click();
        },
        _click: function() {
            $(".filter_btn").off().on("click", function() {
                $("[role=group]").find(".filter_btn").removeClass("ls-active");
                $(this).addClass('ls-active');
                var attr_name = $(this).attr("data-name");
                var data = {};
                data[attr_name] = attr_name;
                $(".filter_input").val("");
                ContaReceber.methods._clearSubCategoria();
                ContaReceber.methods._filter_conta_receber(data);
            });
            $("[name=addPago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                ContaReceber.methods._trocar_status_pgt(data);
            });
            $("[name=removePago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                ContaReceber.methods._trocar_status_pgt(data);
            });
            $("#removeFiltro").off().on("click", function() {
            	$("[role=group]").find(".filter_btn").removeClass('ls-active');
            	$(".filter_input").val("");
            	ContaReceber.methods._clearSubCategoria();
            	var data = {
            		mes_atual:"mes_atual"
            	}
                ContaReceber.methods._filter_conta_receber(data);
            });
            $("[name=removerLancamento]").on("click", function() {
                var conf = confirm("Deseja realmente excluir?");
                if (conf) {
                    Init.methods._loader();
                    var id = $(this).attr("data-id");
                    location.href = $("#URL").val() + "financeiro/lancamentos/excluir/" + id+"/conta_receber";
                }
            });
            $("[name=submit]").on("click", function(){
                if($("[name=descricao]").val() != "" &&
                    $("[name=id_conta]").val() != "" &&
                    $("[name=categoria]").val() != "" &&
                    $("[name=status_pgt]").val() != "" &&
                    $("[name=forma_pgt]").val() != "") {
                    Init.methods._loader();
                }
            });
        },
        _change: function() {
            $("[name=categoria]").on("change", function() {
                if (this.value != "") {
                    ContaReceber.methods._listaSubCategorias(this.value);
                } else {
                    ContaReceber.methods._clearSubCategoria();
                }
            });
            $(".filter_input").on("change", function() {
                $("[role=group]").find(".filter_btn").removeClass('ls-active');
                var object = {};
                $(".filter_input").each(function(){
                	if(this.value != ""){
                		var name = $(this).attr("name");
                		object[name] = $(this).val();
                 	}
                });
                ContaReceber.methods._filter_conta_receber(object);
            });
        },
        _trocar_status_pgt: function(data) {
            Init.methods._loader("#loader", false);
            var url = "financeiro/conta_receber/trocar_status_pgt";
            Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    var attr_name = $("[role=group]").find(".ls-active").attr("data-name");
                    var flag_input = false;
                    var object_input = {};
                    $(".filter_input").each(function(){
                    	if(this.value != "") {
                    		var name = $(this).attr("name");
                    		object_input[name] = this.value;
                    		flag_input = true;
                    	}
                    });
                    if (typeof attr_name != "undefined") {
                        var object_btn = {};
                        object_btn[attr_name] = attr_name;
                        ContaReceber.methods._filter_conta_receber(object_btn);
                    } else if (flag_input) {
                        ContaReceber.methods._filter_conta_receber(object_input);
                    } else {
                    	object = {
                    		mes_atual:"mes_atual"
                    	}
                        ContaReceber.methods._filter_conta_receber(object);
                    }
                } else {}
            });
        },
        _listaSubCategorias: function(id_categoria) {
            var data = {
                id_categoria: id_categoria
            };
            var url = "financeiro/util_control/listar_sub_categorias";
            Init.methods._loader("[name=sub_categoria]", false, "../");
            Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    ContaReceber.methods._clearSubCategoria();
                    var option = $("[name=sub_categoria]");
                    var data = responseData.data;
                    for (var i in data) {
                        option.append($("<option/>", {
                            value: data[i].id
                        }).text(data[i].nome));
                    }
                } else {
                    ContaReceber.methods._clearSubCategoria();
                }
                Init.methods._removeLoader("[name=sub_categoria]");
            });
        },
        _clearSubCategoria: function() {
            $("[name=sub_categoria]").empty().append($("<option/>", {
                value: ""
            }).text("Selecione"));
        },
        _requisition: "",
        _filter_conta_receber: function(data) {
            var url = "financeiro/conta_receber/filter_conta_receber";
            Init.methods._loader("#loader", false);
            if (ContaReceber.methods._requisition) ContaReceber.methods._requisition.abort();
            ContaReceber.methods._requisition = Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    $("table").DataTable().destroy();
                    var data = responseData.data;
                    $("#tbodyContaReceber").empty();
                    $("#total").text(data.total);
                    for (var i in data) {
                        if (typeof data[i] == "object") {
                            var tr = $("<tr/>");
                            tr.append($("<td/>").append($("<a/>", {
                                "href": $("#URL").val() + "financeiro/conta_receber/editar/" + data[i].id,
                                "title": "Editar"
                            }).append($("<i/>", {
                                "class": "ls-ico-pencil"
                            })), $("<a/>", {
                                "title": "Remover",
                                "name": "removerLancamento",
                                "data-id": data[i].id,
                                "href": "#"
                            }).append($("<i/>", {
                                "class": "ls-ico-remove"
                            }))));
                            tr.append($("<td/>").text(data[i].descricao), $("<td/>").text(data[i].data), $("<td/>").text(data[i].categoria + ":" + data[i].sub_categoria), $("<td/>").text(data[i].valor), $("<td/>").text(data[i].id_usuario));
                        }
                        $("#tbodyContaReceber").append(tr);
                    }
                    $("table").dataTable({
                        "oLanguage": Init.methods._configDataTableLanguage
                    });
                } else {
                    $("#tbodyContaReceber").empty();
                    $("#total").text("R$ 0,00");
                }

                ContaReceber.methods._click();
                Init.methods._removeLoader("#loader");
            });
        }
    }
    ContaReceber.methods._init();
})(window.ContaReceber = window.PagarConta || {}, $);