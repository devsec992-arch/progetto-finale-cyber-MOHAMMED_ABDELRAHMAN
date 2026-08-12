<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class BlockSuspiciousIPs
{
    protected  $maxRequests = 5;
    protected  $decayMinutes = 1;
    protected  $blocksMinutes = 10;
    // Numero massimo di richieste consentite
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        //$key = 'suspicious_ip:' . $ip;
        $key=$this->throttleKey($ip);
        if(Cache::has($key.'_blocked')){
            return response("Accesso negato. Il tuo IP è stato bloccato per {$this->blocksMinutes} minuti a causa di attività sospette.");
            }
            if(cache::has($key)){
                $attempts = cache::increment($key);
                if($attempts > $this->maxRequests){
                    cache::put($key.'_blocked', true, now()->addMinutes($this->blocksMinutes));
                    cache::forget($key);
                    session::flash('error', 'Accesso negato. Il tuo IP è stato bloccato per attività sospette.');
                    return response('Accesso negato. Il tuo IP è stato bloccato per attività sospette.');
                }
            }else{
                cache::put($key, 1, now()->addMinutes($this->decayMinutes));
            }
        return $next($request);
    }
    protected function throttleKey($ip)
    {
        return 'throttle:' . sha1($ip);
    }
}