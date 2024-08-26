<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API",
 *     version="1.0.0",
 *     description="API handling the authentication and CRUD for Customers",
 *     @OA\Contact(
 *         email="clarkjohn@gmail.com"
 *     ),
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     )
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Localhost API Server"
 * )
 *
 * */
abstract class Controller
{
    //
}
