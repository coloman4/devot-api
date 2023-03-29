<?php

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Register Request",
 *      description="Register Request request body data",
 *      type="object",
 *      required={"name", "email", "password"}
 * )
 */
class RegisterRequest {

    /**
     * @OA\Property(
     *      title="name",
     *      description="Category name",
     *      example="Car expenses"
     * )
     *
     * @var string
     */
    public $name;

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
     *      example="canttouchthis123"
     * )
     *
     * @var string
     */
    public $password;
    
}
