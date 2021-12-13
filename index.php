<!DOCTYPE html>
<html lang="es">
<head>
<title>Obtener lugares cercanos</title>
<meta charset="utf-8" />
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiF4gBsPZY7fg88JJt5DFaBeMJ0knyKiE&libraries=places"></script>

</head>
<body onload="initMap()">
 <h1>obtener lugares cercanos</h1>
 <div id="mapa" style="width: 450px; height: 350px;"> </div>
 <script>
    var map;
    var infowindow;

    function initMap(){
        // Creamos un mapa con las coordenadas actuales
        navigator.geolocation.getCurrentPosition(function(pos) {

            lat = pos.coords.latitude;
            lon = pos.coords.longitude;

            var myLatlng = new google.maps.LatLng('19.3083386', '-98.8422694');
            console.log(myLatlng)
           // 19.3083386,-98.8422694
            var mapOptions = {
                center: myLatlng,
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.SATELLITE
            };

            map = new google.maps.Map(document.getElementById("mapa"),  mapOptions);

            // Creamos el infowindow
            infowindow = new google.maps.InfoWindow();

            // Especificamos la localización, el radio y el tipo de lugares que queremos obtener
            var request = {
                location: myLatlng,
                radius: 2000,
                types: ['cafe','church','clothing_store','dentist','fire_station',
                        'food','gas_station','funeral_home','hospital']
            };

            // Creamos el servicio PlaceService y enviamos la petición.
            var service = new google.maps.places.PlacesService(map);

            service.nearbySearch(request, function(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        crearMarcador(results[i]);
                    }
                }
            });
        });
    }

    function crearMarcador(place){

        var distancia = getKilometros(19.3083386,-98.8422694,place.geometry.location.lat(),place.geometry.location.lng());
        console.log(place.name,distancia)


        // Creamos un marcador
        var marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
        });

        // Asignamos el evento click del marcador
        google.maps.event.addListener(marker, 'click', function() {
            if(place.photos) {
                //console.log("Images",place.photos[0].getUrl({'maxWidth': 350, 'maxHeight': 350}),)
            }
            /*var service = new google.maps.places.PlacesService(map);
            service.getDetails({
            placeId: place.place_id
            }, function (placeDetails, status) {
                if (status == google.maps.places.PlacesServiceStatus.OK) {
                    console.log("details",placeDetails)
                    console.log("Coordenadas",placeDetails.geometry.location.lat(),placeDetails.geometry.location.lng());
                    console.log(placeDetails.address_components)
                    infowindow.setContent(placeDetails.name);
                };
            });*/

           

            //console.log("Coordenadas", place.geometry.location.lat(), place.geometry.location.lng())
            /*console.log("Namne", place.name);
            console.log("vicinity", place.vicinity);*/
            
           
            infowindow.setContent(place.name);
            infowindow.open(map, this);
        });
    }

    getKilometros = function(lat1,lon1,lat2,lon2){
        rad = function(x) {return x*Math.PI/180;}
        var R = 6378.137; //Radio de la tierra en km
        var dLat = rad( lat2 - lat1 );
        var dLong = rad( lon2 - lon1 );
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(rad(lat1)) * Math.cos(rad(lat2)) * Math.sin(dLong/2) * Math.sin(dLong/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d.toFixed(3); //Retorna tres decimales
    }
 </script>
</body>
</html>
