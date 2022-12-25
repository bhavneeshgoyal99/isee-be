<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class User extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|exists:users,email',
                'password' => 'required',
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                $response = failResponse($validator->errors()->first());
            } else {
                if (Auth::attempt($request->input())) {
                    $user = Auth::user();

                    $response = successResponse('Login Successfull', $user);
                } else {
                    $response = failResponse('Invalid Credentials');
                }
            }
        } catch (Exception $e) {
            $response = failResponse($e);
        }

        return response()->json($response, $response['status']);
    }

    public function register(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'user_type' => 'required',
            ];

            $validator = Validator::make($request->input(), $rules);
            if ($validator->fails()) {
                $response = failResponse($validator->errors()->first());
            } else {
                $userInput = $request->input();
                unset($userInput['policy']);
                $userInput['password'] = Hash::make($userInput['password']);
                $user = ModelsUser::create($userInput);
                if ($user) {
                    $loggedIn = Auth::attempt($userInput);

                    $response = successResponse('Registered Successfully', Auth::user());
                } else {
                    $response = failResponse();
                }
            }
        } catch (Exception $e) {
            $response = failResponse($e);
        }

        return response($response, $response['status']);
    }
}
