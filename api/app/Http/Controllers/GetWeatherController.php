<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        $cacheKey = "weather.{$lat},{$lon}";
        $data = Cache::get($cacheKey);

        if (!$data) {
            $apiKey = config('services.openweather.key');
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=imperial&appid=$apiKey");
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                // Cache the data for 30 minutes
                Cache::put($cacheKey, $data, now()->addMinutes(30));
            } else {
                $data = [
                    'error' => 'Unable to fetch weather data',
                ];
            }
        }

        return response()->json($data);
    }
}
