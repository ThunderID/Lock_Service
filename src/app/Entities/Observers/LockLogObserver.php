<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

use App\Entities\LockLog as Model; 

/**
 * Used in LockLog model
 *
 * @author cmooy
 */
class LockLogObserver 
{
	public function created($model)
	{
		$prev 			= Model::parent($model->parent)->notid($model->_id)->orderby('created_at', 'desc')->first();

		if(!is_null($prev))
		{
			$prev->next 	= $model->_id;
			$prev->save();
		}

		return true;
	}
}
