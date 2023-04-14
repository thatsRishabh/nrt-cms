<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
// use Auth;
use DB;
use Exception;
use Mail;
// use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    //
    public function login(Request $request)
	{
		$validation = Validator::make($request->all(),  [
			'email'                      => 'required|email',
			'password'                  => 'required',

		]);
		if ($validation->fails()) {
			return  prepareResult(false,'validation failed' ,[], 500);
		}

		try 
		{
			$user = User::select('id','name','email','image','role_id','password')->where('email', $request->email)->first();
			if (!empty($user)) {

				if (Hash::check($request->password, $user->password)) {
					$user['token'] = $user->createToken('authToken')->accessToken;
					// $role   = Role::where('id', $user->role_id)->first();
					// $user['permissions']  = $role->permissions()->select('id','se_name', 'group_name','belongs_to')->get();
					return prepareResult(true,'logged in successfully' ,$user, 200);
				}
				else 
				{
					return  prepareResult(false,'wrong Password' ,[], 500);
				} 
			} else {
				return prepareResult(false,'user not found' ,[], 500);    
			}
		} catch (\Throwable $e) {
			Log::error($e);
			return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
		}
	}

	public function logout(Request $request)
	{
		if (Auth::check()) 
		{
			try
			{
				$token = Auth::user()->token();
				$token->revoke();
				auth('api')->user()->tokens->each(function ($token, $key) {
					$token->delete();
				});
				return prepareResult(true,'Logged Out Successfully' ,[], 200); 
			} catch (\Throwable $e) {
				Log::error($e);
				return prepareResult(false,'Oops! Something went wrong.' ,$e->getMessage(), 500);
			}
		}
	}
}
