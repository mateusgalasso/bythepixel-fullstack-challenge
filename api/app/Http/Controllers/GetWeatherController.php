<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GetWeatherController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        // validate
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);
        $lat = $request->lat;
        $lon = $request->lon;

        $weather = new WeatherService();
        $data = $weather->getWeather($lat, $lon);


        return response()->json($data);
    }
}
