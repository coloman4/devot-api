<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Login Request",
 *      description="Login Request request body data",
 *      type="object",
 *      required={"email", "password"}
 * )
 */
class LoginRequest {

    /**
     * @OA\Property(
     *      title="email",
     *      description="User email",
     *      example="myemail@gmail.com"
     * )
     *
     * @var string
     */
    public $email;
    
    /**
     * @OA\Property(
     *      title="password",
     *      description="Password",
     *      example="secret"
     * )
     *
     * @var string
     */
    public $password;
    
}
