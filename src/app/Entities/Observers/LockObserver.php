<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

use App\Entities\Lock as Model; 
use App\Entities\LockLog; 

/**
 * Used in Lock model
 *
 * @author cmooy
 */
class LockObserver 
{
	public function creating($model)
	{
		do
		{
			$key			= uniqid(16);
			$padlock		= uniqid(16);

			$exists_key 	= Model::key($key)->first();
			$exists_padlock = Model::padlock($padlock)->first();
		}
		while($exists_key && $exists_padlock);

		$model->key 		= $key;
		$model->padlock 	= $padlock;

		return true;
	}

	public function created($model)
	{
		$log 				= new LockLog;
		$attr 				= $model['attributes'];
		$attr['parent']		= $model->_id;

		$log->fill($attr);
		$log->save();

		return true;
	}

	public function updating($model)
	{
		$log 			= new LockLog;
		$attr 			= $model['attributes'];
		$attr['parent']	= $attr['_id'];
		unset($attr['_id']);

		$log->fill($attr);
		$log->save();

		return true;
	}
}
