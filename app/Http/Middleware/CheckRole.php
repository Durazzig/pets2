<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        //dd($role);
        $correcto = false;
        $temp = explode(" ", $role);
        for ($i=0; $i < count($temp); $i++) { 
            if ($request->user()->hasRole($temp[$i])) {
                $correcto = true;
                break;
            }
            $correcto = false;
            
        }
        if ($correcto == false) {
            return redirect('error');
        }else{
            return $next($request);
        }
    }
}
