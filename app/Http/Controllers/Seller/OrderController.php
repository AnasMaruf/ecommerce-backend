<?php

namespace App\Http\Controllers\Seller;

use App\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $query = Auth::user()->orderAsSeller()->with([
            'user',
            'address',
            'items',
            'lastStatus'
        ])->isPaid();

        if (request()->last_status) {
            $query->whereHas('lastStatus', function($subQuery){
                $subQuery->where('status', request()->last_status);
            });
        }

        if (request()->search) {
            $query->whereHas('user', function($subQuery){
                $subQuery->where('name', 'LIKE', '%' . request()->search . '%');
            })->orWhere('invoice_number', 'LIKE', '%' . request()->search . '%');

            $productIds = \App\Models\Product\Product::where('name', 'LIKE', '%' . request()->search . '%')->pluck('id');
            $query->orWhereHas('items', function($subQuery) use($productIds) {
                $subQuery->whereIn('product_id', $productIds);
            });
        }

        $orders = $query->paginate(request()->per_page ?? 10);

        return ResponseFormatter::success($orders->through(function($order){
            return $order->api_response_seller;
        }));
    }

    public function show(string $uuid)
    {
        $order = Auth::user()->orderAsSeller()->with([
            'user',
            'address',
            'items',
            'lastStatus'
        ])->isPaid()->where('uuid', $uuid)->firstOrFail();

        return ResponseFormatter::success($order->api_response_seller);
    }


}
