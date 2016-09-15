<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

use App\Entities\Client as Model; 

/**
 * Used in CLient model
 *
 * @author cmooy
 */
class ClientObserver 
{
	public function creating($model)
	{
		return $this->unique_key(0, $model->key, $model);
	}

	public function saving($model)
	{
		$model->secret 			= $this->rehash($model->secret);

		return true;
	}

	public function updating($model)
	{
		return $this->unique_key($model->id, $model->key, $model);
	}

	public function unique_key($id, $key, $model)
	{
		$key 					= Model::notid($id)->key($key)->first();

		if($key)
		{
			$model['errors']	= ['Key harus unique!'];

			return false;
		}

		return true;
	}

	public function rehash($secret)
	{
		if (Hash::needsRehash($secret)) 
		{
			return Hash::make($secret);
		}
	}
}
