<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserLogin
{

    public function handle(Request $request, Closure $next,$role,$guard = null)
    {

        if (!Auth::guard($guard)->check()) {

            if($guard === "user"){
                // return redirect('admin_logout');
                abort(404);
            } else {
                // return redirect('logout');
                abort(404);
            }

        }

        $user=Auth::guard($guard)->user();
        if($guard === "user"){
            if($user->level==='a' && $user->active==='Y'){
                return $next($request);
            }
        } else {
            if($role=='dpt' && auth()->guard('pemilih')->user()->role==='dpt' && $user->aktif==='Y'){
                return $next($request);
            }
        }


        if($guard === "user"){
        abort(404);
        } else {
        abort(404);
        }

    }
}
