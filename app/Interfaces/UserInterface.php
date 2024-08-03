<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\DataTransferObjects\UserDto;

interface UserInterface {
    public function register(UserDto $request);
    public function login(Request $request);
    public function logout();
    public function getAllUsers(Request $request);
    public function getUserById($id);
    public function updateUser(UserDto $request,$id);
    public function deleteUser($id);
}
