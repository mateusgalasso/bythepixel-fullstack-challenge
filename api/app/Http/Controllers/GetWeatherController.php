<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class GetWeatherController extends Controller
{
    public function __construct(protected WeatherService $weatherService)
    {
    }
    public function __invoke(Request $request, User $user)
    {
        // validate
        $request->validate([
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);
        $data = $this->weatherService->getWeather($request->lat, $request->lon);

        return response()->json($data);
    }
}
