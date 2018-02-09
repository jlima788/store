(function(Conhecimento, $) {
    Conhecimento.methods = Conhecimento.methods || {
        _load: function() {
            Conhecimento.methods._click();
            Conhecimento.methods._change();
        },
        _click: function() {
            $("#addServico").on("click", function(){
                var appendServico = $("#appendServico");
                var modelServico = $("#modelServico").clone().removeAttr("id").addClass("modelServico");
                modelServico.find("[name=delServico]").on("click", function(){
                    modelServico.remove();
                });
                appendServico.append(modelServico.show());
                modelServico.find("input[type=text]").on("change", function() {
                    var valor =parseFloat($("#vlr_servico_soma").val());
                    $(".modelServico").find("input[type=text]").each(function(){
                        if(this.value != "")
                            valor += parseFloat(this.value.replace(/[\.]/g, "").replace(",", "."));
                    });
                    $("[name=vlr_servico]").val(accounting.formatMoney(valor, {
                        decimal: ",",
                        thousand: ".",
                        symbol: ""
                    })).trigger("change");
                });
                Main.methods._mask();
            });
            $("[name=removerServicos]").on("click", function() {
                var conf = confirm("Deseja realmente excluir?");
                if (conf) {
                    var parent = $(this).parent();
                    var valor = parseFloat(parent.find("input[type=text]").val().replace(/[\.]/g, "").replace(",", "."));
                    var subtracao = parseFloat($("[name=vlr_servico]").val().replace(/[\.]/g, "").replace(",", ".") - valor);
                    $("[name=vlr_servico]").val(accounting.formatMoney(subtracao, {
                        decimal: ",",
                        thousand: ".",
                        symbol: ""
                    })).trigger("change");
                    parent.remove();
                }
            });
        },
        _change: function() {
            var vlr_icms_base_calculo = $(".vlr_icms_base_calculo").trigger("change");
            vlr_icms_base_calculo.on("change", function() {
                var valor = parseFloat("0.00");
                $(".vlr_icms_base_calculo").each(function() {
                    if (this.value != "") {
                        valor += parseFloat(this.value.replace(/[\.]/g, "").replace(",", "."));
                    }
                });
                $("[name=vlr_icms_base_calculo]").val(accounting.formatMoney(valor, {
                    decimal: ",",
                    thousand: ".",
                    symbol: ""
                })).trigger("change");
                $("[name=vlr_prestacao]").val(accounting.formatMoney(valor, {
                    decimal: ",",
                    thousand: ".",
                    symbol: ""
                })).trigger("change");
            });
            var vlr_icms = $(".vlr_icms").trigger("change");
            vlr_icms.on("change", function() {
                var valor = (parseFloat($("[name=vlr_icms_base_calculo]").val().replace(/[\.]/g, "").replace(",", ".")) * parseFloat($("[name=vlr_icms_percentual]").val().replace(",", ".")) / 100);
                $("[name=vlr_icms]").val(accounting.formatMoney(valor, {
                    decimal: ",",
                    thousand: ".",
                    symbol: ""
                })).trigger("change");
            });
            $(".modelServicos").find("input[type=text]").on("change", function() {
                var valor = parseFloat($("#vlr_servico_soma").val());
                var subtracao = parseFloat($(this).val().replace(/[\.]/g, "").replace(",", ".") - $(this).next().val());
                valor += subtracao;
                $("[name=vlr_servico]").val(accounting.formatMoney(valor, {
                    decimal: ",",
                    thousand: ".",
                    symbol: ""
                })).trigger("change");
            });
            Main.methods._mask();
        }
    }
    Conhecimento.methods._load();
})(window.Conhecimento = window.Conhecimento || {}, $);