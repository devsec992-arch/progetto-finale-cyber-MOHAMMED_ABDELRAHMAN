<?php

namespace App\Livewire;

use GuzzleHttp\Client;
use Livewire\Component;
use App\Services\HttpService;
use Illuminate\Support\Facades\Log;

class LatestNews extends Component
{
    public $selectedApi;
    public $news;
    protected $httpService;
   

    public function __construct()
    {
        $this->httpService = app(HttpService::class);
    }

    public function fetchNews()
    {
        if (filter_var($this->selectedApi, FILTER_VALIDATE_URL) === FALSE) {
            $this->news = 'Invalid URL';
            return;
        }

 // Block localhost, loopbacks, and private internal container subnets/ports (like port 8001 or 127.0.0.1)
        $blockedHosts = ['localhost', '127.0.0.1', '::1', '0.0.0.0'];
        $host = parse_url($this->selectedApi, PHP_URL_HOST);
        if (in_array($host, $blockedHosts, true) || str_starts_with($host, '192.168.') || str_starts_with($host, '10.')) {
            Log::warning("SSRF_ATTACK_BLOCKED: Attempted raw access to internal host [{$host}] from IP [" . request()->ip() . "]");
            $this->news = 'Access to internal network destinations is forbidden.';
            return;
        }





        $this->news = json_decode($this->httpService->getRequest($this->selectedApi), true);

    }
    public function render()
    {
        return view('livewire.latest-news');
    }
}
