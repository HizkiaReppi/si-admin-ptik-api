<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'student',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'lecturer_id_1' => $validatedData['lecturer_id'],
                'nim' => $validatedData['username'],
                'entry_year' => '',
                'concentration' => 'RPL',
            ]);

            event(new Registered($user));

            DB::commit();

            Auth::login($user);
            
            $token = $user->createToken('authToken');
            
            return ApiResponseClass::sendResponse(201, 'User registered successfully.', [
                'token' => $token->plainTextToken,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponseClass::sendError(500, 'User registration failed.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
