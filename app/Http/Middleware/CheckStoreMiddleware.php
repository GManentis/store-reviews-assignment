<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Store;

class CheckStoreMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $store = Store::find((int)$request->route("storeId"));
        if(!$store) return response()->json(["message" => "Δεν βρέθηκε το κατάστημα"], 404); 
        $request->merge(["store" => $store]);
        return $next($request);
    }
}
