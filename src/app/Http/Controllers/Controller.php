<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    /**
     * @OA\Info(
     * description="",
     * version="1.0.0",
     * title="API Documentation",
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
