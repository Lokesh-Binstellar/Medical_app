<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $module, $permission): Response
    {
        // dd($module);
        // dd($request->user()->hasRoleCRUDPermission($module, $permission,$request->user()->id));
       
        if (Auth::user()->role_id != 1 && !$request->user()->hasRoleCRUDPermission($module, $permission)) {
            // dd(Auth::user());
            abort(401, 'This action is unauthorized.');
            
        }
        return $next($request);
    }
}
