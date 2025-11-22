<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Endpoints de registro, login y logout de usuarios"
 * )
*/

class ApiUserController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/register",
 *     summary="Registrar nuevo usuario",
 *     tags={"Autenticación"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name","email","password"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Usuario registrado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Usuario registrado correctamente"),
 *             @OA\Property(property="token", type="string", example="l|asdkjasdkljlaklsjdkj")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Error en los datos de validación"
 *     )
 * )
 */


    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario Registrado Correctamente',
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Iniciar sesión y obtener token",
     *     tags={"Autenticación"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login exitoso con token"),
     *     @OA\Response(response=401, description="Credenciales inválidas")
     * )
     */
    public function login(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'token' => $token
            ]);
        }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Cerrar sesión (revocar token)",
     *     tags={"Autenticación"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logout exitoso")
     * )
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }

}
