<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Steadfast
{
    private $base_url = 'https://portal.packzy.com/api/v1'; // Replace with the actual base URL
    private $api_key = 'euzouwqaiy8eihwmgesthxyenwpni8qj';
    private $secret_key = 'jlrlligvpiejyqt84x0dlk68';

    public function bulkCreate($data)
    {
        $response = Http::withHeaders([
            'Api-Key' => $this->api_key,
            'Secret-Key' => $this->secret_key,
            'Content-Type' => 'application/json',
        ])->post($this->base_url . '/create_order/bulk-order', [
            'data' => $data,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getStatusByCid($data)
    {
        $response = Http::withHeaders([
            'Api-Key' => $this->api_key,
            'Secret-Key' => $this->secret_key,
            'Content-Type' => 'application/json',
        ])->get($this->base_url . '/status_by_cid/116536295');
        return json_decode($response->getBody()->getContents());
    }
}
