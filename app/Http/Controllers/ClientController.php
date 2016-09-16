<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\Client;

/**
 * Client resource representation.
 *
 * @Resource("Clients", uri="/clients")
 */
class ClientController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all Clients
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"_id":"string","type":"mobile|web","version":"string","code":"string","grant":"string","scopes":"string"},"sort":{"newest":"asc|desc","version":"desc|asc","type":"desc|asc", "company":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":{"_id":"string","app":{"type":"string","version":"string","name":"string"},"company":{"code":"string","name":"string"},"key":"string","secret":"string","grants":{"name":"string","scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Client;

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
					case 'type':
						$result		= $result->apptype($value);
						break;
					case 'version':
						$result		= $result->appversion($value);
						break;
					case 'code':
						$result		= $result->companycode($value);
						break;
					case 'grant':
						$result		= $result->grantname($value);
						break;
					case 'scopes':
						$result		= $result->grantscopes($value);
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
					case 'newest':
						$result		= $result->orderby('created_at', $value);
						break;
					case 'version':
						$result		= $result->orderby('app.version', $value);
						break;
					case 'type':
						$result		= $result->orderby('app.type', $value);
						break;
					case 'company':
						$result		= $result->orderby('company.name', $value);
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
	 * Store Client
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"_id":null,"app":{"type":"string","version":"string","name":"string"},"company":{"code":"string","name":"string"},"key":"string","secret":"string","grants":{"name":"string","scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}}),
	 *      @Response(200, body={"status": "success", "data": {"_id":"string","app":{"type":"string","version":"string","name":"string"},"company":{"code":"string","name":"string"},"key":"string","secret":"string","grants":{"name":"string","scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('_id');

		if(!is_null($id) && !empty($id))
		{
			$result		= Client::id($id)->first();
		}
		else
		{
			$result 	= new Client;
		}
		

		$result->fill(Input::only('app', 'company', 'key', 'secret', 'grants', 'expire'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete Client
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"_id":null,"app":{"type":"string","version":"string","name":"string"},"company":{"code":"string","name":"string"},"key":"string","secret":"string","grants":{"name":"string","scopes":{"string"}},"expire":{"scheduled":{"timezone":"string","hour":"integer"},"timeout":{"minute":"integer"}}}}),
	 *      @Response(200, body={"status": {"error": {"code must be unique."}}})
	 * })
	 */
	public function delete()
	{
		$client			= Client::id(Input::get('_id'))->first();

		$result 		= $client;

		if($client && $client->delete())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		elseif(!$client)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($client->getError())->asArray());
	}
}