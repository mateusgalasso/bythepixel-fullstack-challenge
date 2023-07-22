<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WeatherService;
use Illuminate\Http\Request;

class GetUserWeatherController extends Controller
{
    public function __construct(protected WeatherService $weatherService)
    {
    }

    public function __invoke(Request $request, User $user)
    {
        // retrieve the weather data
        return response()->json($this->weatherService->getWeather(lat: $user->latitude, lon: $user->longitude));
    }
}
