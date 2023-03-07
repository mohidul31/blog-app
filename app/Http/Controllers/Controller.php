<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="Blog App API Documentation",
 *    version="1.0.0",
 *      @OA\Contact(
 *         name="H M Mohidul Islam",
 *         email="mohidul@du.ac.bd"
 *     ),
 * ),
  * @OA\Server(
 *     url="http://127.0.0.1:8000",
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
