<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="username", type="string", example="john"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User registered successfully"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Log in a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"identity","password"},
     *             @OA\Property(property="identity", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Logged in successfully"),
     *     @OA\Response(response=401, description="Invalid credentials")
     * )
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'identity' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => [
            'user' => $user->name,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'token' => $token,
            'modules' => $this->getModules($user)
        ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Log out a user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logged out successfully")
     * )
     */
    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Loggedout successfully']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    private function getModules(User $user)
    {
        return [
            [
                'label' => 'nombre_modulo_1',
                'ico' => 'imagen',
                'modules' => [
                    [
                        'label' => 'nombre_submodulo_1',
                        'ico' => 'imagen',
                        'url' => 'ruta_correspondiente_en_el_front',
                        'model' => 'modelo_asociado',
                        'permissions' => [
                            'has_add' => true,
                            'has_edit' => true,
                            'has_delete' => false,
                            'has_history' => false,
                            'has_show' => true,
                            'has_otro_definido' => true,
                        ],
                    ],

                ],
            ],

        ];
    }
}

