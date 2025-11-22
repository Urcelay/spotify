<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="AndersSpotify API",
 *     version="1.0",
 *     description="Documentación de la API para el proyecto de reproducción musical tipo Spotify",
 *     @OA\Contact(
 *         email="fernandaurcelay5@gmail.com"
 *     )
 * )
 *
 * @OA\Server(
 *     url=LS_SWAGGER_CONST_HOST,
 *     description="Servidor local"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Usa el token generado tras el login. Formato: Bearer (token)",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="sanctum"
 *)
 */


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
