<?php

namespace App\Http\Middleware;

use App\Models\Machine;
use Closure;
use Illuminate\Http\Request;

class VerifyMachineToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        $machine = Machine::with('servers')->where('token', $token)->first();

        if (!$machine) {
            abort(403);
        }

        if ($machine->ip == null) {
            $machine->ip = $request->ip();
            $machine->save();
        } elseif ($machine->ip != $request->ip()) {
            abort(403);
        }

        $request->machine = $machine;

        return $next($request);
    }
}
