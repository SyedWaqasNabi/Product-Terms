<?php

namespace Tests;

use App\Models\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    const STATUS_CODE_SUCCESS = 200;
    const STATUS_CODE_NOT_AUTHENTICATED = 401;
    const STATUS_CODE_BAD_REQUEST = 400;
    const DRIVER = "web";
    const TRADE_ITEM_OFFER_REST = "/api/v1/product/terms";

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function authenticatedUser()
    {
        return $user = \factory(user::class)->create();
    }

    public function authenticationHeaders()
    {
        $headers =[
            "HTTP_Authorization" => "Basic " . base64_encode(env('BASIC_AUTH_USERNAME') . ":" . env('BASIC_AUTH_PASSWORD')),
            "PHP_AUTH_USER" => env('BASIC_AUTH_USERNAME') ,
            "PHP_AUTH_PW" => env('BASIC_AUTH_PASSWORD')
        ];
        return $headers;
    }
}
