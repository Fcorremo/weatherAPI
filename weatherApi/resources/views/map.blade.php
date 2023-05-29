<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/styles.css">
<style>
       
        #map {
            height: 80%;
            width: 100%;
        }
        .map-container {
            max-height: 100px; 
            margin-bottom: 20px; 
        }
    </style>
  </head>
  <body>
  <select id="ciudadSelect">
        <option value="">Selecciona una ciudad</option>
        @foreach ($ciudades as $ciudad)
            <option value="{{ $ciudad }}">{{ $ciudad }}</option>
        @endforeach
    </select>
    
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('ciudadSelect').addEventListener('change', function() {
            var ciudad = this.value;
            if (ciudad) {
                // Mover el mapa a la ciudad seleccionada
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({ address: ciudad }, function(results, status) {
                    if (status === 'OK') {
                        var location = results[0].geometry.location;
                        map.setCenter(location);
                    } else {
                        console.log('Error al geocodificar la dirección: ' + status);
                    }
                });

                // Realizar la solicitud POST al controlador
                var url = '/api/historial';
                var data = {
                    ciudad: ciudad
                };

                axios.post(url, data)
                    .then(function(response) {
                        console.log('Registro almacenado exitosamente');
                    })
                    .catch(function(error) {
                        console.log('Error al almacenar el registro: ' + error);
                    });
            }
        });
    </script>
    
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h1 class="text-center">Mapa</h1>
                <div id="map" class="w-100"></div>
            </div>
            <div class="col-md-5">
                <h2 class="text-center">Registros</h2>
                <table class="table table-dark table-hover" id="tablaRegistros">
                    <thead>
                        <tr>
                            <th class="text-center">Ciudad</th>
                            <th class="text-center">Humedad</th>
                            <th class="text-center">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registros as $registro)
                            <tr>
                                <td class="text-center">{{ $registro->ciudad }}</td>
                                <td class="text-center">{{ $registro->humedad }}%</td>
                                <td class="text-center">{{ $registro->created_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('API_KEY_MAP') }}&callback=initMap" async defer></script>
    <script>
        // Lógica JavaScript para cargar el mapa y mostrar los datos
        var map;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 25.7617, lng: -80.1918},
                zoom: 10
            });


        }
    </script>
    <script>
        // Función para actualizar la tabla de registros
        function actualizarTabla() {
            axios.get('/api/historial')
                .then(function(response) {
                    var registros = response.data;
    
                    // Generar el HTML para las filas de la tabla
                    var htmlFilas = '';
                    registros.forEach(function(registro) {
                        htmlFilas += '<tr>' +
                            '<td>' + registro.ciudad + '</td>' +
                            '<td>' + registro.humedad + '%</td>' +
                            '<td>' + registro.created_at + '</td>' +
                            '</tr>';
                    });
    
                    // Actualizar el contenido de la tabla
                    var tabla = document.getElementById('tablaRegistros');
                    tabla.innerHTML = htmlFilas;
                })
                .catch(function(error) {
                    console.log('Error al obtener los registros: ' + error);
                });
        }
    
        // Actualizar la tabla cada 2 segundos (ajusta el intervalo según tus necesidades)
        setInterval(actualizarTabla, 2000);
    </script>
      <footer>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>