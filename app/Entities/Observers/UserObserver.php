<?php 

namespace App\Entities\Observers;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Hash;

use App\Entities\User as Model; 

/**
 * Used in User model
 *
 * @author cmooy
 */
class UserObserver 
{
	public function creating($model)
	{
		return $this->unique_email(0, $model->email, $model);
	}

	public function saving($model)
	{
		$model->password 	= $this->rehash($model->password);

		return true;
	}

	public function updating($model)
	{
		return $this->unique_email($model->id, $model->email, $model);
	}

	public function unique_email($id, $email, $model)
	{
		$email 				= Model::notid($id)->email($email)->first();

		if($email->count())
		{
			$model['errors'] = ['Email harus unique!'];

			return false;
		}

		return true;
	}

	public function rehash($password)
	{
		if (Hash::needsRehash($password)) 
		{
			return Hash::make($password);
		}
	}
}
