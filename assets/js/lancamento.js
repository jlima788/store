(function(Lancamento, $) {
    Lancamento.methods = Lancamento.methods || {
        _init: function() {
            Lancamento.methods._change();
            Lancamento.methods._click();
        },
        _click: function() {
            $(".filter_btn").off().on("click", function() {
                $("[role=group]").find(".filter_btn").removeClass("ls-active");
                $(this).addClass('ls-active');
                var attr_name = $(this).attr("data-name");
                var data = {};
                data[attr_name] = attr_name;
                $(".filter_input").val("");
                Lancamento.methods._clearSubCategoria();
                Lancamento.methods._filter_lancamento(data);
            });
            $("[name=addPago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                Lancamento.methods._trocar_status_pgt(data);
            });
            $("[name=removePago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                Lancamento.methods._trocar_status_pgt(data);
            });
            $("#removeFiltro").off().on("click", function() {
                $("[role=group]").find(".filter_btn").removeClass('ls-active');
                $(".filter_input").val("");
                Lancamento.methods._clearSubCategoria();
                var data = {
                    mes_atual: "mes_atual"
                }
                Lancamento.methods._filter_lancamento(data);
            });
            $("[name=editar]").off().on("click", function() {
                var url = $("#URL").val() + $(this).attr("data-target");
                var janela = window.open(url, "Editar", "width=1200, height=800");
            });
            $("[name=removerLancamento]").on("click", function() {
                var conf = confirm("Deseja realmente excluir?");
                if (conf) {
                    var id = $(this).attr("data-id");
                    location.href = $("#URL").val()+"financeiro/lancamento/remover/" + id;
                }
            });
        },
        _change: function() {
            $("[name=categoria]").on("change", function() {
                if (this.value != "") {
                    Lancamento.methods._listaSubCategorias(this.value);
                } else {
                    Lancamento.methods._clearSubCategoria();
                }
            });
            $(".filter_input").on("change", function() {
                $("[role=group]").find(".filter_btn").removeClass('ls-active');
                var object = {};
                $(".filter_input").each(function() {
                    if (this.value != "") {
                        var name = $(this).attr("name");
                        object[name] = $(this).val();
                    }
                });
                Lancamento.methods._filter_lancamento(object);
            });
        },
        _dataTableDestroy: function() {
            $('table').DataTable().destroy();
        },
        _trocar_status_pgt: function(data) {
            var url = "financeiro/lancamento/trocar_status_pgt";
            Main.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    location.reload();
                } else {

                }
            });
        },
        _listarOnFilter: function() {
            var attr_name = $("[role=group]").find(".ls-active").attr("data-name");
            var flag_input = false;
            var object_input = {};
            $(".filter_input").each(function() {
                if (this.value != "") {
                    var name = $(this).attr("name");
                    object_input[name] = this.value;
                    flag_input = true;
                }
            });
            if (typeof attr_name != "undefined") {
                var object_btn = {};
                object_btn[attr_name] = attr_name;
                Lancamento.methods._filter_lancamento(object_btn);
            } else if (flag_input) {
                Lancamento.methods._filter_lancamento(object_input);
            } else {
                object = {
                    mes_atual: "mes_atual"
                }
                Lancamento.methods._filter_lancamento(object);
            }
        },
        _listaSubCategorias: function(id_categoria) {
            var data = {
                id_categoria: id_categoria
            };
            var url = "financeiro/util_control/listar_sub_categorias";
            Main.methods._loader("[name=sub_categoria]", false, "../");
            Main.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    Lancamento.methods._clearSubCategoria();
                    var option = $("[name=sub_categoria]");
                    var data = responseData.data;
                    for (var i in data) {
                        option.append($("<option/>", {
                            value: data[i].id
                        }).text(data[i].nome));
                    }
                } else {
                    Lancamento.methods._clearSubCategoria();
                }
                Main.methods._removeLoader("[name=sub_categoria]");
            });
        },
        _clearSubCategoria: function() {
            $("[name=sub_categoria]").empty().append($("<option/>", {
                value: ""
            }).text("Selecione"));
        },
        _requisition: "",
        _filter_lancamento: function(data) {
            Lancamento.methods._dataTableDestroy();
            var url = "financeiro/lancamento/filter_lancamento";
            Main.methods._loader("#loader", false);
            if (Lancamento.methods._requisition) Lancamento.methods._requisition.abort();
            Lancamento.methods._requisition = Main.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    var data = responseData.data;
                    $("#tbodyLancamentos").empty();
                    $("#total").text(data.total);
                    for (var i in data) {
                        if (typeof data[i] == "object") {
                            var tr = $("<tr/>");
                            tr.append($("<td/>").append($("<a/>", {
                                "href": "#", //"financeiro/conta_pagar/editar/" + data[i].id,
                                "title": "Editar",
                                "data-id": data[i].id,
                                "data-target": data[i].tipo_pgt == "P" ? "conta_pagar/editar/" + data[i].id : "conta_receber/editar/" + data[i].id,
                                "name": "editar"
                            }).append($("<i/>", {
                                "class": "ls-ico-pencil"
                            })), $("<a/>", {
                                "title": "Remover",
                                "name": "removerLancamento",
                                "data-id": data[i].id,
                                "href": "#"
                            }).on("click", function() {
                                var conf = confirm("Deseja realmente excluir?");
                                if (conf) {
                                    var id = $(this).attr("data-id");
                                    location.href = "financeiro/lancamento/remover/" + id;
                                }
                            }).append($("<i/>", {
                                "class": "ls-ico-remove"
                            }))));
                            if (data[i].forma_pgt == "1") {
                                data[i].forma_pgt = "Cheque";
                            } else if (data[i].forma_pgt == "2") {
                                data[i].forma_pgt = "Deposito";
                            } else if (data[i].forma_pgt == "3") {
                                data[i].forma_pgt = "Duplicata";
                            }
                            tr.append($("<td/>").text(data[i].tipo_pgt == "P" ? "Contas a pagar" : "Contas a receber"), $("<td/>").text(data[i].descricao), $("<td/>").text(data[i].data), $("<td/>").text(data[i].categoria + ":" + data[i].sub_categoria), $("<td/>").text(data[i].id_usuario), $("<td/>").text(data[i].valor), $("<td/>").text(data[i].forma_pgt));
                            if (data[i].status_pgt == "1") {
                                tr.append($("<td/>").append($("<a/>", {
                                    "href": "#",
                                    "name": "removePago",
                                    "class": "ls-tooltip-top",
                                    "data-name": data[i].status_pgt,
                                    "data-id": data[i].id,
                                    "aria-label": "Sim, Click para altera"
                                }).append($("<i/>", {
                                    "title": "Sim, click para altera",
                                    "name": "tooltip",
                                    "data-placement": "top",
                                    "class": "ls-ico-checkmark-circle"
                                }))));
                            } else {
                                tr.append($("<td/>").append($("<a/>", {
                                    "href": "#",
                                    "name": "addPago",
                                    "class": "ls-tooltip-top",
                                    "data-name": data[i].status_pgt,
                                    "data-id": data[i].id,
                                    "aria-label": "Não, Click para altera"
                                }).append($("<i/>", {
                                    "title": "Não, click para altera",
                                    "name": "tooltip",
                                    "data-placement": "top",
                                    "class": "ls-ico-info"
                                }))));
                            }
                        }
                        $("#tbodyLancamentos").append(tr);
                    }
                } else {
                    $("#tbodyLancamentos").empty();
                    $("#total").text("R$ 0,00");
                }
                $("table").dataTable({
                    "oLanguage": Main.methods._configDataTableLanguage
                });
                Lancamento.methods._click();
                Main.methods._removeLoader("#loader");
            });
        }
    }
    Lancamento.methods._init();
})(window.Lancamento = window.Lancamento || {}, $);