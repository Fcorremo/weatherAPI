<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Historial;

class HistorialController extends Controller
{
    /**
     * Obtiene el historial de humedad.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historial = Historial::orderBy('fecha', 'desc')->get();

        return response()->json($historial);
        return view('mapa');
    }

    /**
     * Almacena un nuevo registro de humedad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validar y procesar los datos de entrada...
    $request->validate([
        'ciudad' => 'required|string',
    ]);

    $ciudad = $request->input('ciudad');
    $apiKey = env('API_KEY_EXT');

    // Realizar la solicitud GET al API de OpenWeatherMap
    $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey");

    // Obtener la humedad de la respuesta
    $humedad = $response['main']['humidity'];

    // Almacenar el registro en la base de datos
    $historial = new Historial();
    $historial->ciudad = $ciudad;
    $historial->humedad = $humedad;
    $historial->save();

    // Retornar una respuesta adecuada
    return response()->json([
        'message' => 'Registro almacenado exitosamente',
        'data' => $historial,
    ], 201);
}


public function getCiudades()
{
    $ciudades = Historial::pluck('ciudad')->unique()->toArray();
    return $ciudades;
}

public function map()
{
    $registros = Historial::all(); // Obtén los registros de la tabla 'historials'
    $ciudades = $this->getCiudades();
    return view('map', compact('registros', 'ciudades')); // Pasa los registros a la vista
}



public function showMap($ciudad)
{
    

    return view('ciudad-map', compact('ciudad'));
}

public function getHumedad($ciudad)
{
    $apiKey = env('API_KEY_EXT');

    // Realizar la solicitud GET al API de OpenWeatherMap
    $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q=$ciudad&appid=$apiKey");

    // Obtener la humedad de la respuesta
    $humedad = $response['main']['humidity'];

    // Retornar la humedad en formato JSON
    return response()->json(['humedad' => $humedad]);
}



}

