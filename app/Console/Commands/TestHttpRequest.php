<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestHttpRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:http-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test HTTP requests to external APIs in NativePHP Mobile';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing HTTP requests in NativePHP Mobile...');
        
        // Test 1: Simple HTTP GET request
        $this->info('📡 Test 1: Simple HTTP GET request to httpbin.org');
        try {
            $response = Http::timeout(10)->get('https://httpbin.org/get');
            if ($response->successful()) {
                $this->info('✅ HTTP GET request successful!');
                $this->info('Status: ' . $response->status());
                $this->info('Response size: ' . strlen($response->body()) . ' bytes');
            } else {
                $this->error('❌ HTTP GET request failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('❌ HTTP GET request exception: ' . $e->getMessage());
            Log::error('HTTP GET test failed', ['error' => $e->getMessage()]);
        }
        
        // Test 2: Test to the CargoPanel API
        $this->info('📡 Test 2: HTTP GET request to CargoPanel API');
        try {
            $response = Http::timeout(10)->get('https://v4.cargopanel.app/api/admin/contactos');
            if ($response->successful()) {
                $this->info('✅ CargoPanel API request successful!');
                $this->info('Status: ' . $response->status());
                $this->info('Response size: ' . strlen($response->body()) . ' bytes');
            } else {
                $this->error('❌ CargoPanel API request failed with status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('❌ CargoPanel API request exception: ' . $e->getMessage());
            Log::error('CargoPanel API test failed', ['error' => $e->getMessage()]);
        }
        
        // Test 3: Test cURL functions
        $this->info('📡 Test 3: Testing cURL functions availability');
        if (function_exists('curl_init')) {
            $this->info('✅ cURL extension is available');
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://httpbin.org/get');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($result !== false && $httpCode == 200) {
                $this->info('✅ cURL request successful!');
                $this->info('HTTP Code: ' . $httpCode);
            } else {
                $this->error('❌ cURL request failed: ' . $error);
            }
        } else {
            $this->error('❌ cURL extension is not available');
        }
        
        // Test 4: Test file_get_contents with HTTP
        $this->info('📡 Test 4: Testing file_get_contents with HTTP');
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'user_agent' => 'NativePHP-Test/1.0'
            ]
        ]);
        
        try {
            $result = file_get_contents('https://httpbin.org/get', false, $context);
            if ($result !== false) {
                $this->info('✅ file_get_contents HTTP request successful!');
                $this->info('Response size: ' . strlen($result) . ' bytes');
            } else {
                $this->error('❌ file_get_contents HTTP request failed');
            }
        } catch (\Exception $e) {
            $this->error('❌ file_get_contents HTTP request exception: ' . $e->getMessage());
        }
        
        // Test 5: Check environment variables
        $this->info('📡 Test 5: Checking environment variables');
        $this->info('APP_URL: ' . env('APP_URL', 'NOT_SET'));
        $this->info('NATIVEPHP_RUNNING: ' . env('NATIVEPHP_RUNNING', 'NOT_SET'));
        $this->info('HTTP_HOST: ' . env('HTTP_HOST', 'NOT_SET'));
        
        $this->info('🧪 HTTP request testing completed!');
        
        return 0;
    }
}

