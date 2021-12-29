<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

class ProductImportTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public $http = "";
    public $params = [
        "photo" => NULL,
        "file" => NULL,
        "product_type_id" => 1,
        "product_type_id" => "Manual",
        "purchasing_type_id" => NULL,
        "transaction_id" => NULL
    ];

    public function setUp(): void
    {
        parent::setup();

        $this->http = new Client(['base_uri' => env("APP_URL", "http://stocksite.local")]);
    }

    public function tearDown(): void {
        $this->http = null;
    }


    public function test_all_field_is_required_and_status_is_200()
    {
        $response = $this->http->request('POST', '/admin/products/save-file', [
            'headers' => [
                'Content-Type', 'multipart/form-data'
            ],
            'form_params' => $this->params
        ]);

        
    }
}
