(function(Mapa, $) {
    Mapa.methods = Mapa.methods || {
        _cancelAjax: "",
        _destino: "",
        _origem: "",
        _Place: "",
        _directionsDisplay: "",
        _markers: [],
        _pedagio: false,
        _map: "",
        _directionsService: directionsService = new google.maps.DirectionsService(),
        _load: function() {
            Mapa.methods._click();
            Mapa.methods._loadMap();
            Mapa.methods._initialize();
            Mapa.methods._routesDiretion();
            $("[name=vl_litro]").mask("#,##0.00", {reverse: true});
            $("[name=km_litro]").mask("#,##0.00", {reverse: true});
            $("[name=vl_litro], [name=km_litro]").on("change", function(){
                $("[name=submit]").click();
            });
        },
        _click: function() {},
        _loadMap: function() {
            Mapa.methods._directionsDisplay = new google.maps.DirectionsRenderer();
            Mapa.methods._map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: -23.928484,
                    lng: -46.38338
                },
                scrollwheel: true,
                zoom: 10
            });
            Mapa.methods._directionsDisplay.setMap(Mapa.methods._map);
            Mapa.methods._directionsDisplay.setPanel(document.getElementById("trajeto-texto"));
        },
        _initialize: function() {
            Mapa.methods._destino = new google.maps.places.Autocomplete((document.getElementById('origem')), {
                types: ['geocode']
            });
            Mapa.methods._origem = new google.maps.places.Autocomplete((document.getElementById('destino')), {
                types: ['geocode']
            });
        },
        _routesDiretion: function() {
            $("#formMapa").submit(function(event) {
                event.preventDefault();
                var origem = $("#origem").val();
                var destino = $("#destino").val();
                var request = {
                    origin: origem,
                    destination: destino,
                    travelMode: google.maps.TravelMode.DRIVING,
                    avoidTolls: Mapa.methods._pedagio
                };
                Mapa.methods._directionsService.route(request, function(result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        var routes = result.routes[0];
                        var distance = routes.legs[0].distance;
                        var duration = routes.legs[0].duration;
                        $("#duration").text(duration.text);
                        $("#distance").text(distance.text);
                        var distance_value = distance.value / 1000;
                        Mapa.methods._directionsDisplay.setDirections(result);
                        Mapa.methods._mapGeometry(result, distance_value);
                    }
                });
            });
        },
        _mapGeometry: function(result, distance_value) {
            var d = [];
            for (var c = 0; c < result.routes.length; c++) {
                for (var f = 0; f < result.routes[c].legs.length; f++) {
                    for (var e = 0; e < result.routes[c].legs[f].steps.length; e++) {
                        for (var a = 1; a < result.routes[c].legs[f].steps[e].path.length; a++) {
                            d.push(result.routes[c].legs[f].steps[e].path[a])
                        }
                    }
                }
            }
            var g = google.maps.geometry.encoding.encodePath(d);
            Main.methods._post("api/mapeia",{g:g}).done(function(response){
                Mapa.methods._clearMarkers();
                var tolls = response.tolls;
                var total_pedagio = 0;
                for(var i in tolls) {
                    total_pedagio += tolls[i].price;
                    valor_formatado = accounting.formatMoney(tolls[i].price, "R$ ", 2, ".", ",");
                    var d = '<div class="info-window"><div class="info-window-name">' + tolls[i].name + '</div><div class="info-window-price">R$ ' + valor_formatado + "</div></div>";
                    var f = new google.maps.InfoWindow({
                        content: d,
                        maxWidth: 300
                    });
                    var c = new google.maps.Marker({
                        position: {
                            lat: tolls[i].lat,
                            lng: tolls[i].long
                        },
                        map: Mapa.methods._map,
                        title: valor_formatado,
                        infowindow: f
                        //animation: google.maps.Animation.DROP
                    });
                    google.maps.event.addListener(c, 'click', function() {
                        this.infowindow.open(Mapa.methods._map, this);
                    });
                    Mapa.methods._markers.push(c);
                }
                var vl_litro = $("[name=vl_litro]").val() != "" ? $("[name=vl_litro]").val().replace(",","") : 0.00;
                var km_litro = $("[name=km_litro]").val() != "" ? $("[name=km_litro]").val().replace(",","") : 0.00;
                var total_estimado = total_pedagio + (vl_litro * (distance_value / km_litro));
                $("#pedagios").text(accounting.formatMoney(total_pedagio, "R$ ", 2, ".", ","));
                if(!isNaN(total_estimado))
                    $("#valor_estimado").text(accounting.formatMoney(total_estimado, "R$ ", 2, ".", ","));
            });
        },
        _clearMarkers: function() {
            Mapa.methods._setMapOnAll(null);
        },
        _setMapOnAll: function(map) {
            for (var i = 0; i < Mapa.methods._markers.length; i++) {
                Mapa.methods._markers[i].setMap(map);
            }
        }
    }
    Mapa.methods._load();
})(window.Mapa = window.Mapa || {}, $);