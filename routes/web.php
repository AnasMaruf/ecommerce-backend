<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/testing', function () {
//     $response = \Http::withHeaders(
//         [
//             'key' => config('services.rajaongkir.key')
//         ]
//     )->withOptions(['verify' => false])
//     ->post(config('services.rajaongkir.base_url'). '/cost', [
//         'origin' => 27,
//         'destination' => 33,
//         'weight' => 1000,
//         'courier' => 'jne'
//     ]);

//     $result = collect($response->object()->rajaongkir->results)->map(function($item){
//         return [
//             'cost' => collect($item->costs)->map(function($cost){
//                 return [
//                     "service" => $cost->service,
//                     "description" => $cost->description,
//                     "etd"=> $cost->cost[0]->etd,
//                     "value" => $cost->cost[0]->value
//                 ];
//             })
//         ];
//     })[0];
//     dd($result);
// });
