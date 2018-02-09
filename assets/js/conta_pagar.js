(function () {
    ContaPagar.methods = ContaPagar.methods || {
        _init: function () {
            ContaPagar.methods._change();
            ContaPagar.methods._click();
        },
        _click: function () {
            $(".filter_btn").off().on("click", function () {
                $("[role=group]").find(".filter_btn").removeClass("ls-active");
                $(this).addClass('ls-active');
                var attr_name = $(this).attr("data-name");
                var data = {};
                data[attr_name] = attr_name;
                $(".filter_input").val("");
                ContaPagar.methods._clearSubCategoria();
                ContaPagar.methods._filter_conta_pagar(data);
            });
            $("[name=addPago]").off().on("click", function () {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                ContaPagar.methods._trocar_status_pgt(data);
            });
            $("[name=removePago]").off().on("click", function () {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                ContaPagar.methods._trocar_status_pgt(data);
            });
            $("#removeFiltro").off().on("click", function () {
                $("[role=group]").find(".filter_btn").removeClass('ls-active');
                $(".filter_input").val("");
                ContaPagar.methods._clearSubCategoria();
                var data = {
                    mes_atual: "mes_atual"
                }
                ContaPagar.methods._filter_conta_pagar(data);
            });
            $("[name=removerLancamento]").on("click", function () {
                var conf = confirm("Deseja realmente excluir?");
                if (conf) {
                    var id = $(this).attr("data-id");
                    location.href = $("#URL").val() + "financeiro/lancamento/excluir/" + id + "/contas_pagar";
                }
            });
            $("[name=submit]").on("click", function () {
                if ($("[name=descricao]").val() != "" &&
                        $("[name=id_conta]").val() != "" &&
                        $("[name=categoria]").val() != "" &&
                        $("[name=status_pgt]").val() != "" &&
                        $("[name=forma_pgt]").val() != "") {
                    Main.methods._loader();
                }
            });
        },
        _change: function () {
            $("[name=categoria]").on("change", function () {
                if (this.value != "") {
                    ContaPagar.methods._listaSubCategorias(this.value);
                } else {
                    ContaPagar.methods._clearSubCategoria();
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
        _trocar_status_pgt: function (data) {
            var url = "financeiro/conta_pagar/trocar_status_pgt";
            Main.methods._post(url, data).done(function (responseData) {
                if (responseData.status) {
                    var attr_name = $("[role=group]").find(".ls-active").attr("data-name");
                    var flag_input = false;
                    var object_input = {};
                    $(".filter_input").each(function () {
                        if (this.value != "") {
                            var name = $(this).attr("name");
                            object_input[name] = this.value;
                            flag_input = true;
                        }
                    });
                    if (typeof attr_name != "undefined") {
                        var object_btn = {};
                        object_btn[attr_name] = attr_name;
                        ContaPagar.methods._filter_conta_pagar(object_btn);
                    } else if (flag_input) {
                        ContaPagar.methods._filter_conta_pagar(object_input);
                    } else {
                        object = {
                            mes_atual: "mes_atual"
                        }
                        ContaPagar.methods._filter_conta_pagar(object);
                    }
                } else {
                }
            });
        },
        _listaSubCategorias: function (id_categoria) {
            var data = {
                id_categoria: id_categoria
            };
            var url = "financeiro/categoria/listar_sub_categorias";
            Main.methods._post(url, data).done(function (responseData) {
                if (responseData.status) {
                    ContaPagar.methods._clearSubCategoria();
                    var option = $("[name=sub_categoria]");
                    var data = responseData.data;
                    for (var i in data) {
                        option.append($("<option/>", {
                            value: data[i].id
                        }).text(data[i].nome));
                    }
                } else {
                    ContaPagar.methods._clearSubCategoria();
                }
            });
        },
        _clearSubCategoria: function () {
            $("[name=sub_categoria]").empty().append($("<option/>", {
                value: ""
            }).text("Selecione"));
        },
        _requisition: ""
    }
    ContaPagar.methods._init();
})(window.ContaPagar = window.PagarConta || {}, $);