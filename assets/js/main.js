(function(Main, $) {
    Main.methods = Main.methods || {
        _cancelAjax: "",
        _post: function(url, data, defaultUrl) {
            var address = $("#URL").val() + url;
            if (defaultUrl)
                address = url;
            return $.post(address, {
                data: JSON.stringify(data)
            }, function() {}, "json");
        },
        _load: function() {
            Main.methods._click();
            Main.methods._onlyNumber();
            Main.methods._hideMsg();
            Main.methods._mask();
        },
        _mask: function() {
            $(".datepicker-here").mask("00/00/0000");
            $(".containerMask").mask("AAAA 000000-0").on("keyup",function(){
                if(this.value != "")
                    this.value = this.value.toUpperCase();
            });
            $(".placaMask").mask("AAA 0000").on("keyup",function(){
                if(this.value != "")
                    this.value = this.value.toUpperCase();
            });
            $(".moneyMask").maskMoney({
                prefix: 'R$ ',
                allowNegative: true,
                thousands: '.',
                decimal: ',',
                affixesStay: false,
                allowZero: true,

            });
            $(".numberMask").maskMoney({
                allowNegative: true,
                thousands: '',
                decimal: '.',
                affixesStay: false,
                allowZero: true
            });
            $(".numberMaskTara").maskMoney({
                allowNegative: false,
                thousands: '.',
                decimal: ',',
                affixesStay: false,
                allowZero: true,
                precision: 3
            });
            $(".numberNotFloat").maskMoney({
                allowNegative: true,
                thousands: '',
                decimal: '',
                affixesStay: false,
                allowZero: true
            });
        },
        _click: function() {},
        _validForm: function(element) {
            if(element == undefined)
                element = "form";
            $(".msgError").remove();
            var flag = true;
            $(element).find("input, textarea, select").each(function(){
                var span = $("<span/>",{class:'msgError'}).text("Required field");
                if(this.value == "") {
                    flag = false;
                    $(span).insertAfter(this);
                    $(this).on({
                        keyup: function() {
                            if(this.value != "")
                                span.remove();
                        },
                        change: function() {
                            if(this.value != "") {
                                span.remove();
                            }
                        }
                    });
                } else if($(this).attr("type") == "checkbox"){
                    if(!$(this).is(":checked")) {
                        flag = false;
                        $(span).insertAfter(this);
                        $(this).on({
                            change: function() {
                                if(this.value != "") {
                                    span.remove();
                                }
                            }
                        });
                    }
                }
            });
            return flag;
        },
        _onlyNumber: function(){
            $(".onlyNumber").on("keypress", function(e) {
                var tecla = (window.event) ? event.keyCode : e.which;
                if ((tecla > 47 && tecla < 58)) {
                    return true;
                }
                else {
                    if (tecla == 8 || tecla == 0) {
                        return true;
                    }
                    else return false;
                }
            });
        },
        _hideMsg: function() {
            setTimeout(function(){
                $('.ls-main-custom').remove();
            },7000)
        },
        _loader: function(element, windowSize) {
            if (typeof element == "undefined") element = "body";
            if (typeof windowSize == "undefined") windowSize = true;
            $(element).oLoader({
                wholeWindow: windowSize, //makes the loader fit the window size
                lockOverflow: true, //disable scrollbar on body
                backgroundColor: '#000',
                fadeLevel: 0.4,
                image: $("#URL").val() + 'assets/img/loader5.gif'
            });
        },
        _removeLoader: function(element) {
            if (typeof element == "undefined") element = "body";
            $(element).oLoader('hide');
        }
    }
    Main.methods._load();
})(window.Main = window.Main || {}, $);
