(function() {
    FluxoCaixa.methods = FluxoCaixa.methods || {
        _init: function() {
            FluxoCaixa.methods._change();
            FluxoCaixa.methods._click();
            $("table").dataTable({
                "oLanguage": Init.methods._configDataTableLanguage
            });
        },
        _click: function() {
            $(".filter_btn").off().on("click", function() {
                $("[role=group]").find(".filter_btn").removeClass("active");
                $(this).addClass('active');
                var attr_name = $(this).attr("data-name");
                var data = {};
                data[attr_name] = attr_name;
                $(".filter_input").val("");
                FluxoCaixa.methods._clearSubCategoria();
                FluxoCaixa.methods._filter_fluxo_caixa(data);
            });
            $("[name=addPago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                FluxoCaixa.methods._trocar_status_pgt(data);
            });
            $("[name=removePago]").off().on("click", function() {
                var data = {
                    status_pgt: $(this).attr("data-name"),
                    id: $(this).attr("data-id")
                };
                FluxoCaixa.methods._trocar_status_pgt(data);
            });
            $("#removeFiltro").off().on("click", function() {
                $("[role=group]").find(".filter_btn").removeClass('active');
                $(".filter_input").val("");
                FluxoCaixa.methods._clearSubCategoria();
                var data = {
                    mes_atual: "mes_atual"
                }
                FluxoCaixa.methods._filter_fluxo_caixa(data);
            });
            $("#geraExcel").off().on("click", function() {
                var attr_name = $("[role=group]").find(".active").attr("data-name");
                var flag_input = false;
                var object_input = {};
                object_input.download = '1';
                $(".filter_input").each(function() {
                    if (this.value != "") {
                        var name = $(this).attr("name");
                        object_input[name] = this.value;
                        flag_input = true;
                    }
                });
                if (typeof attr_name != "undefined") {
                    var object_btn = {};
                    object_btn.download = '1';
                    object_btn[attr_name] = attr_name;
                    FluxoCaixa.methods._filter_fluxo_caixa(object_btn);
                } else if (flag_input) {
                    FluxoCaixa.methods._filter_fluxo_caixa(object_input);
                } else {
                    object = {
                        mes_atual: "mes_atual",
                        download: '1'
                    }
                    FluxoCaixa.methods._filter_fluxo_caixa(object);
                }
            });
        },
        _change: function() {
            $("[name=categoria]").on("change", function() {
                if (this.value != "") {
                    FluxoCaixa.methods._listaSubCategorias(this.value);
                } else {
                    FluxoCaixa.methods._clearSubCategoria();
                }
            });
            $(".filter_input").on("change", function() {
                $("[role=group]").find(".filter_btn").removeClass('active');
                var object = {};
                $(".filter_input").each(function() {
                    if (this.value != "") {
                        var name = $(this).attr("name");
                        object[name] = $(this).val();
                    }
                });
                FluxoCaixa.methods._filter_fluxo_caixa(object);
            });
        },
        _trocar_status_pgt: function(data) {
            Init.methods._loader("#loader", false);
            var url = "financeiro/conta_receber/trocar_status_pgt";
            Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    var attr_name = $("[role=group]").find(".active").attr("data-name");
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
                        FluxoCaixa.methods._filter_fluxo_caixa(object_btn);
                    } else if (flag_input) {
                        FluxoCaixa.methods._filter_fluxo_caixa(object_input);
                    } else {
                        object = {
                            mes_atual: "mes_atual"
                        }
                        FluxoCaixa.methods._filter_fluxo_caixa(object);
                    }
                } else {}
            });
        },
        _listaSubCategorias: function(id_categoria) {
            var data = {
                id_categoria: id_categoria
            };
            var url = "financeiro/categoria/listar_sub_categorias";
            Init.methods._loader("[name=sub_categoria]", false, "../");
            Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    FluxoCaixa.methods._clearSubCategoria();
                    var option = $("[name=sub_categoria]");
                    var data = responseData.data;
                    for (var i in data) {
                        option.append($("<option/>", {
                            value: data[i].id
                        }).text(data[i].nome));
                    }
                } else {
                    FluxoCaixa.methods._clearSubCategoria();
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
        _filter_fluxo_caixa: function(data) {
            var url = "financeiro/fluxo_caixa/filter_fluxo_caixa";
            Init.methods._loader("#loader_body", false);
            if (FluxoCaixa.methods._requisition) FluxoCaixa.methods._requisition.abort();
            FluxoCaixa.methods._requisition = Init.methods._post(url, data).done(function(responseData) {
                if (responseData.status) {
                    $("table").DataTable().destroy();
                    var data = responseData.data;
                    $("#tbodyFluxoCaixa").empty();
                    $("#total").text(data.total_fluxo);
                    for (var i in data) {
                        if (typeof data[i] == "object") {
                            var tr = $("<tr/>");
                            tr.append($("<td/>").text(data[i].descricao), $("<td/>").text(data[i].data), $("<td/>").text(data[i].tipo_pgt == "P" ? "Saida" : "Entrada"), $("<td/>").text(data[i].categoria + ":" + data[i].sub_categoria), $("<td/>").text(data[i].valor), $("<td/>").text(data[i].saldo));
                        }
                        $("#tbodyFluxoCaixa").append(tr);
                    }
                    if (typeof responseData.name != "undefined") {
                        location.href = $("#URL").val()+"financeiro/fluxo_caixa/download_fluxo_caixa/" + responseData.name;
                    }
                    $("table").dataTable({
                        "oLanguage": Init.methods._configDataTableLanguage
                    });
                } else {
                    $("#tbodyFluxoCaixa").empty();
                    $("#total").text("R$ 0,00");
                }
                FluxoCaixa.methods._click();
                Init.methods._removeLoader("#loader_body");
            });
        }
    }
    FluxoCaixa.methods._init();
})(window.FluxoCaixa = window.FluxoCaixa || {}, $);