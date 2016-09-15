<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\User;

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */
class UserController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all Users
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"_id":"string","type":"mobile|web","version":"string","code":"string","client":"string","scopes":"string"},"sort":{"newest":"asc|desc","version":"desc|asc","type":"desc|asc", "company":"desc|asc","name":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":{"_id":"string","email":"string","user":{"name":"string"},"accesses":{"client_id":"string","app":{"type":"web|mobile","name":"string","version":"string"},"company":{"code":"string","name":"string"},"scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new User;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case '_id':
						$result		= $result->id($value);
						break;
					case 'email':
						$result		= $result->email($value);
						break;
					case 'type':
						$result		= $result->apptype($value);
						break;
					case 'version':
						$result		= $result->appversion($value);
						break;
					case 'code':
						$result		= $result->companycode($value);
						break;
					case 'client':
						$result		= $result->clientid($value);
						break;
					case 'scopes':
						$result		= $result->accessscopes($value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		if(Input::has('sort'))
		{
			$sort					= Input::get('sort');

			foreach ($sort as $key => $value) 
			{
				if(!in_array($value, ['asc', 'desc']))
				{
					return response()->json( JSend::error([$key.' harus bernilai asc atau desc.'])->asArray());
				}
				switch (strtolower($key)) 
				{
					case 'name':
						$result		= $result->orderby('user.name', $value);
						break;
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'version':
						$result		= $result->orderby('accesses.app.version', $value);
						break;
					case 'type':
						$result		= $result->orderby('accesses.app.type', $value);
						break;
					case 'company':
						$result		= $result->orderby('accesses.company.name', $value);
						break;
					default:
						# code...
						break;
				}
			}
		}

		$count						= count($result->get());

		if(Input::has('skip'))
		{
			$skip					= Input::get('skip');
			$result					= $result->skip($skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take($take);
		}

		$result 					= $result->get();
		
		return response()->json( JSend::success(['data' => $result->toArray(), 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store User
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"_id":"null","email":"string","password":"string","user":{"name":"string"},"accesses":{"client_id":"string","app":{"type":"web|mobile","name":"string","version":"string"},"company":{"code":"string","name":"string"},"scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","email":"string","user":{"name":"string"},"accesses":{"client_id":"string","app":{"type":"web|mobile","name":"string","version":"string"},"company":{"code":"string","name":"string"},"scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('_id');

		if(!is_null($id) && !empty($id))
		{
			$result		= User::id($id)->first();
		}
		else
		{
			$result 	= new User;
		}
		

		$result->fill(Input::only('email', 'password', 'user', 'accesses', 'expire'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete User
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","email":"string","user":{"name":"string"},"accesses":{"client_id":"string","app":{"type":"web|mobile","name":"string","version":"string"},"company":{"code":"string","name":"string"},"scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function delete()
	{
		$user			= User::id(Input::get('_id'))->first();

		$result 		= $user;

		if($user && $user->delete())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		elseif(!$user)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($user->getError())->asArray());
	}
}