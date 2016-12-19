<?php

namespace App\Http\Controllers;

use App\Libraries\JSend;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Entities\Lock;
use App\Entities\LockLog;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * Lock resource representation.
 *
 * @Resource("Locks", uri="/Locks")
 */
class LockController extends Controller
{
	public function __construct(Request $request)
	{
		$this->request 				= $request;
	}

	/**
	 * Show all Locks
	 *
	 * @Get("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"search":{"id":"string","title":"string","writer":"string"},"sort":{"newest":"asc|desc","title":"desc|asc","writer":"desc|asc"}, "take":"integer", "skip":"integer"}),
	 *      @Response(200, body={"status": "success", "data": {"data":{"id":{"value":"123456789","type":"string","max":"255"},"title":{"value":"Template Akta Jual Beli Tanah","type":"string","max":"255"},"type":{"value":"akta|ktp","type":"string","max":"255"},"paragraph":{"value":{"Paragraph 1", "Paragraph 2"},"type":"array"},"writer":{"value":{"name":"Alana"},"type":"array"}},"count":"integer"} })
	 * })
	 */
	public function index()
	{
		$result						= new Lock;

		if(Input::has('search'))
		{
			$search					= Input::get('search');

			foreach ($search as $key => $value) 
			{
				switch (strtolower($key)) 
				{
					case 'id':
						$result		= $result->id($value);
						break;
					case 'pandoraid':
						$result		= $result->pandoraid($value);
						break;
					case 'pandoratype':
						$result		= $result->pandoratype($value);
						break;
					case 'ownerid':
						$result		= $result->ownerid($value);
						break;
					case 'ownertype':
						$result		= $result->ownertype($value);
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
					case 'title':
						$result		= $result->orderby('title', $value);
						break;
					case 'writer':
						$result		= $result->orderby('writer', $value);
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
			$result					= $result->skip((int)$skip);
		}

		if(Input::has('take'))
		{
			$take					= Input::get('take');
			$result					= $result->take((int)$take);
		}

		$result 					= $result->get(['_id', 'key', 'padlock', 'pandora', 'owner'])->toArray();
		
		return response()->json( JSend::success(['data' => $result, 'count' => $count])->asArray())
				->setCallback($this->request->input('callback'));
	}

	/**
	 * Store Lock
	 *
	 * @Post("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null,"title":"string","paragraph":"array","writer":"array"}),
	 *      @Response(200, body={"status": "success", "data": {"id":{"value":"123456789","type":"string","max":"255"},"title":{"value":"Template Akta Jual Beli Tanah","type":"string","max":"255"},"type":{"value":"akta|ktp","type":"string","max":"255"},"paragraph":{"value":{"Paragraph 1", "Paragraph 2"},"type":"array"},"writer":{"value":{"name":"Alana"},"type":"array"}}}),
	 *      @Response(200, body={"status": {"error": {"writer name required."}}})
	 * })
	 */
	public function post()
	{
		$id 			= Input::get('id');

		if(!is_null($id) && !empty($id))
		{
			$result		= Lock::id($id)->first();
		}
		else
		{
			$result 	= new Lock;
		}
		
		$result->fill(Input::only('key', 'padlock', 'pandora', 'owner'));

		if($result->save())
		{
			return response()->json( JSend::success($result->toArray())->asArray())
					->setCallback($this->request->input('callback'));
		}
		
		return response()->json( JSend::error($result->getError())->asArray());
	}

	/**
	 * Delete Lock
	 *
	 * @Delete("/")
	 * @Versions({"v1"})
	 * @Transaction({
	 *      @Request({"id":null}),
	 *      @Response(200, body={"status": "success", "data": {"id":{"value":"123456789","type":"string","max":"255"},"title":{"value":"Template Akta Jual Beli Tanah","type":"string","max":"255"},"type":{"value":"akta|ktp","type":"string","max":"255"},"paragraph":{"value":{"Paragraph 1", "Paragraph 2"},"type":"array"},"writer":{"value":{"name":"Alana"},"type":"array"}}}),
	 *      @Response(200, body={"status": {"error": {"writer name required."}}})
	 * })
	 */
	public function delete()
	{
		$lock		= Lock::id(Input::get('id'))->first();

		$result 	= $lock->toArray();

		if($lock && $lock->delete())
		{
			return response()->json( JSend::success($result)->asArray())
					->setCallback($this->request->input('callback'));
		}
		elseif(!$lock)
		{
			return response()->json( JSend::error(['ID tidak valid'])->asArray());
		}

		return response()->json( JSend::error($lock->getError())->asArray());
	}
}