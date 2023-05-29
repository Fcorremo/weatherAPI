<!DOCTYPE html>
<html>
<head>
    <title>Mapa de la ciudad</title>
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h1>Mapa de la ciudad: {{ $ciudad }}</h1>
    <div id="map"></div>

    <script>
        function initMap() {
            var ciudad = "{{ $ciudad }}";
            var geocoder = new google.maps.Geocoder();
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 0, lng: 0},
                zoom: 8
            });

            geocoder.geocode({ address: ciudad }, function(results, status) {
                if (status === 'OK') {
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY_MAP') }}&callback=initMap" async defer></script>
</body>
</html>
