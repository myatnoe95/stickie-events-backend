<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserDTO
{
    public ?string $user_name;
    public ?string $password;
    public ?string $role;

    public function __construct(
        ?string $user_name,
        ?string $password,
        ?string $role
    ) {
        $this->user_name = $user_name;
        $this->password  = $password;
        $this->role      = $role;

        $this->validate();
    }

    public static function fromRequest(Request $request): UserDTO
    {
        return new self(
            $request->input('user_name'),
            $request->input('password'),
            $request->input('role')
        );
    }

    public function validate()
    {
        $request = (array) $this;

        $rules = [
            'user_name' => 'required|string|max:255',
            'password'  => 'required|string|min:8',
            'role'      => 'required|in:Admin,Member',
        ];

        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
    }

    public function toArray(): array
    {
        return [
            'user_name' => $this->user_name,
            'password'  => $this->password,
            'role'      => $this->role,
        ];
    }
}
