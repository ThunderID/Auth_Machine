<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Libraries\JSend;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\User;
use App\Entities\Client;

use GenTux\Jwt\JwtToken;

/**
 * Auth resource representation.
 *
 * @Resource("Tokens", uri="/tokens")
 */
class AuthController extends Controller
{
	public function __construct(Request $request, JwtToken $jwt)
	{
		$this->request 				= $request;
		$this->jwt 					= $jwt;
	}

	/**
	 * Generate token
	 *
	 * @Post("/generate")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"key":"string","secret":"string","grant":"string","email":"string","password":"string"}),
	 *      @Response(200, body={"status": "success", "data": {"token":{"header":{"alg","typ"}},{"payload":{"iss","exp","content":{"company","scopes","application","user","client_id"}}},"verify signature"}}),
	 *      @Response(200, body={"status": {"error": {"password required."}}})
	 * })
	 */
	public function generate()
	{
		$grant 			= Input::get('grant');

		if(strtolower($grant)=='client_credential')
		{
			return $this->client_credential(Input::only('key','secret','grant'));
		}
		elseif(strtolower($grant)=='password')
		{
			return $this->password(Input::only('key','secret','grant','email','password'));
		}
		
		return response()->json( JSend::error(['Grant type tidak valid'])->asArray());
	}

	public function client_credential($credentials)
	{
		//1. validate key & secret
		$client 		= Client::key($credentials['key'])->first();

		if($client && Hash::check($credentials['secret'], $client['secret']))
		{
			$grant 		= null;

			foreach ($client['grants'] as $key => $value) 
			{
				if($value['name']=='client_credential')
				{
					$grant 	=	$value; 
				}
			}

			if(isset($client['expire']['scheduled']['hour']) && isset($client['expire']['scheduled']['timezone']))
			{
				$expired	= Carbon::createFromFormat('H', $client['expire']['scheduled']['hour'], $client['expire']['scheduled']['timezone']);
			}
			elseif(isset($client['expire']['timeout']['minute']))
			{
				$expired	= Carbon::parse('+ '.$client['expire']['timeout']['minute'].' minutes');
			}
			else
			{
				$expired	= Carbon::parse('+ 2 hours');
			}

			$clientclaims				= 	[
												'iss'		=>	$_SERVER['HTTP_HOST'], 
												'iat'		=> 	time(), 
												'exp'		=>	time() + $expired->second, 
												'nbf'		=> 	time(), 
												// 'jti'		=>	md5($_SERVER['HTTP_HOST'].'.'.Carbon::now()),
												'aud'		=> 'company',
											];

			$clientclaims['content'] 	= 	[
												'client_id' => 	$client['_id'],
												'company' 	=> 	$client['company'],
												'scopes' 	=> 	$grant['scopes'],
												'app' 		=> 	$client['app'],
											];

			$token 						= $this->jwt->createToken($clientclaims); 

			return response()->json( JSend::success(['token' => $token])->asArray())
				->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error(['Key / Secret tidak valid'])->asArray());
	}	

	public function password($credentials)
	{
		//1. validate key & secret
		$client 		= Client::key($credentials['key'])->first();

		if($client && Hash::check($credentials['secret'], $client['secret']))
		{
			$user 		= User::email($credentials['email'])->first();

			if($user && Hash::check($credentials['password'], $user['password']))
			{
				$scopes 		= null;

				foreach ($user['accesses'] as $key => $value) 
				{
					if(strtolower($value['company']['code'])==strtolower($client['company']['code']) && strtolower($value['app']['type'])==strtolower($client['app']['type']))
					{
						$scopes =	$value['scopes']; 
					}
				}

				if(isset($user['expire']['scheduled']['hour']) && isset($user['expire']['scheduled']['timezone']))
				{
					$expired	= Carbon::createFromFormat('H', $user['expire']['scheduled']['hour'], $user['expire']['scheduled']['timezone']);
				}
				elseif(isset($user['expire']['timeout']['minute']))
				{
					$expired	= Carbon::parse('+ '.$user['expire']['timeout']['minute'].' minutes');
				}
				else
				{
					$expired	= Carbon::parse('+ 20 hours');
				}

				$clientclaims				= 	[
													'iss'		=>	$_SERVER['HTTP_HOST'], 
													'iat'		=> 	time(), 
													'exp'		=>	time() + $expired->second, 
													'nbf'		=> 	time(), 
													// 'jti'		=>	md5($_SERVER['HTTP_HOST'].'.'.Carbon::now()),
													'aud'		=> 'user',
												];

				$clientclaims['content'] 	= 	[
													'client_id' => 	$client['_id'],
													'company' 	=> 	$client['company'],
													'scopes' 	=> 	$scopes,
													'app' 		=> 	$client['app'],
													'user' 		=> 	['email' => $user['email'], 'name' => $user['name']],
												];

				$token 						= $this->jwt->createToken($clientclaims); 

				return response()->json( JSend::success(['token' => $token])->asArray())
					->setCallback($this->request->input('callback'));
			}

			return response()->json( JSend::error(['Email / Password tidak valid'])->asArray());
		}
		
		return response()->json( JSend::error(['Key / Secret tidak valid'])->asArray());
	}	
}