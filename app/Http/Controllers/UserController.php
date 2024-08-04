<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseCodes;
use App\Interfaces\UserInterface;
use App\DataTransferObjects\UserDto;
use App\Transformers\UserResource;
use App\Transformers\UserCollection;

class UserController extends Controller
{
    private UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        try{
            $userDto = UserDto::fromRequest($request);
            $user = $this->userRepository->register($userDto);
            return $user;
    
        }catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request)
    {
        $user = $this->userRepository->login($request);

        if ($user == null) {
            return $this->sendError($user,"Data Not Found", 404);
        }
        return $user;
    }

    public function logout()
    {
        $user = $this->userRepository->logout();
        if ($user == null) {
            return $this->sendError($user,"Data Not Found", 404);
        }
        return $user;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->getAllUsers($request);

        if($users == null){
            return $this->sendError($users);
        }else{
            return $this->sendResponse(
                new UserCollection($users['data']),
                ResponseCodes::OK,
                [
                    'total_records' => $users['totalRecords']
                ]
            );
        }
    }

    public function show($id)
    {
        $user = $this->userRepository->getUserById($id);
 
        if ($user == null) {
            return $this->sendError($user);
        }
        return $this->sendResponse(new UserResource($user));
    }

    public function update(Request $request, $id)
    {
        try {
            $userDTO = UserDTO::fromRequest($request,true);
            $user = $this->userRepository->updateUser($userDTO, $id);

            if ($user == null) {
                return $this->sendError($user);
            }
            return $this->sendResponse(new UserResource($user),
            ResponseCodes::CREATED,[
                'message' =>  "User data updated successfully!"
            ]);
        }
        catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $user = $this->userRepository->deleteUser($id);
        if ($user == null) {
            return $this->sendError($user);
        }
        return $this->sendResponse(new UserResource($user));
    }
}
