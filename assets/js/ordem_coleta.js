(function(OrdemColeta, $) {
    OrdemColeta.methods = OrdemColeta.methods || {
        _load: function() {
            OrdemColeta.methods._click();
            $(".modelNF").each(function(){
                $(this).find("input").trigger("change");
            });
        },
        _click: function() {
            $("#addNF").on("click", function() {
                var appendNF = $("#appendNF");
                var modelNF = $("#modelNF").clone().removeAttr("id").addClass("modelNF");
                modelNF.find("input").trigger("change");
                modelNF.find("[name=delNF]").on("click", function(){
                    modelNF.remove();
                });
                appendNF.append(modelNF.show());
                Main.methods._mask();
            });
        }
    }
    OrdemColeta.methods._load();
})(window.OrdemColeta = window.OrdemColeta || {}, $);