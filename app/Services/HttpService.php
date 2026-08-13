<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class HttpService
{
    protected $client;
    protected $allowedDomains = ['internal.finance','newsapi.org'];
    protected $allowedProtocols = ['http', 'https'];
    protected $refererHeader; // Intestazione Referer
   
    public function __construct()
    {
        $this->refererHeader = config('app.url');
        $this->client = new Client();
    }

    public function getRequest($url)
    {
        
        $parsedUrl = parse_url($url);

        // Validate protocol
        if (!in_array($parsedUrl['scheme'], $this->allowedProtocols)) {
            return 'Protocol not allowed';
        }
       
        // Validate domain
        if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $this->allowedDomains)) {
            return 'Domain not allowed';
        }

        if ($parsedUrl['host'] === 'internal.finance') {
            if (!Auth::check() || Auth::user()->role !== 'is_admin') {
                $email = Auth::check() ? Auth::user()->email : 'Guest';
                
                Log::alert("SSRF_ROLE_VIOLATION: user email [{$email}]  attempted to force a server request to internal.finance from IP [" . request()->ip() . "]");
                
                
                abort(403, 'Access Denied: You do not have the required administrator privileges.');
            }
        }




        // Aggiungi l'intestazione Referer per le richieste al server locale
        $options['headers'] = ['Referer' => $this->refererHeader];

        try {
            $response = $this->client->request('GET', $url, $options);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            return 'Something went wrong: ' . $e->getMessage();
        }
    }
}
