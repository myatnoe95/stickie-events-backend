<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Interfaces\UserInterface;
use App\Transformers\UserResource;
use App\DataTransferObjects\UserDto;

class UserRepository implements UserInterface{

	public function register(UserDto $request)
    {
		$existingUser = User::where('user_name', $request->user_name)->first();

		if ($existingUser) {
			return response()->json([
				'message' => 'Username already taken!',
			], 409);
		}

        $user   = new User();

        $userDBT  = DB::transaction(function () use ($user, $request) {
            $request->password = Hash::make($request->password);
            $user->fill($request->toArray())->save();
           
			return response()->json([
				'message' => 'User Created Successfully!',
                'user' => new UserResource($user),
            ],200);
        });
        return $userDBT;
    }

	public function login(Request $request)
    {
        $user = User::where('user_name',$request->user_name)->first();
        if(!$user)
        {
            return response()->json([
                'message' => 'Username Not Found!',
            ], 401);
        }

        if (!Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid email or password!',
            ], 401);
        }

        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken('token')->accessToken;
            
            return response()->json([
                'user' => new UserResource($user),
                'access_token' => $token
            ],200);
        }

        return $user;
    }

	public function logout()
    {
        auth()->user()->token()->revoke();
        return response([
            'message' => 'Successfully Logout!',
        ], 200);
    }


    public function getAllUsers(Request $request)
    {
		$columns = ['user_name'];
        $pageIndex = $request->input('pageIndex');
        $length = $request->input('pageSize');

		$start = $length * $pageIndex - $length;
        $search = $request->input('query');

        $query = User::query();

		if (!empty($search)) {
            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }

        $totalRecords = $query->count();

		if (!empty($length)) {
            $query->offset($start);
        }
        $query->limit($length);

        $data = $query->get();
        $result = [
            'totalRecords'  => $totalRecords,
            'data'          => $data,
        ];

        return $result;
    }

    public function getUserById($id)
    {
        $user = User::where('id', $id)->first();

		return $user == null ? null : $user;
    }

	public function updateUser(UserDto $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            return null;
        }

        $userDBT = DB::transaction(function () use ($user, $request) {
            $user->fill($request->toEntity())->save();
            return $user;
        });

        return $userDBT;
    }

    public function deleteUser($id)
    {
		$user = User::find($id);

		if ($user == null) {
			return null;
		}

		$userDBT = DB::transaction(function () use ($user) {
			$user->delete();
			return $user;
		});

		return $userDBT;
    }
}
