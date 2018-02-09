(function(Solicitacao, $) {
    Solicitacao.methods = Solicitacao.methods || {
        _load: function() {
            Solicitacao.methods._click();
        },
        _click: function() {
            $("#addContainer").on("click", function(){
                var appendContainer = $("#appendContainer");
                var modelContainer = $("#modelContainer").clone().removeAttr("id").addClass("modelContainer");
                modelContainer.find("[name=delContainer]").on("click", function(){
                    modelContainer.remove();
                });
                appendContainer.append(modelContainer.show());
                Main.methods._mask();
            });
        }
    }
    Solicitacao.methods._load();
})(window.Solicitacao = window.Solicitacao || {}, $);