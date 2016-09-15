<?php

namespace App\Entities;

use App\Entities\Observers\UserObserver;

/**
 * Used for User Models
 * 
 * @author cmooy
 */
class User extends BaseModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection			= 'mt_users';

	/**
	 * Date will be returned as carbon
	 *
	 * @var array
	 */
	protected $dates				=	['created_at', 'updated_at', 'deleted_at'];

	/**
	 * The appends attributes from mutator and accessor
	 *
	 * @var array
	 */
	protected $appends				=	[];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden 				= ['password'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable				=	[
											'email'							,
											'password'						,
											'user'							,
											'accesses'						,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'email'							=> 'required|email|max:255',
											'user.name'						=> 'required|max:255',
											'accesses.*.client_id'			=> 'required|max:255',
											'accesses.*.app.type'			=> 'required|in:web,mobile',
											'accesses.*.app.name'			=> 'required|max:255',
											'accesses.*.app.version'		=> 'required|max:255',
											'accesses.*.company.code'		=> 'required|max:255',
											'accesses.*.company.name'		=> 'required|max:255',
											'accesses.*.scopes.*'			=> 'required|max:255',
											'expire.scheduled.timezone'		=> 'timezone',
											'expire.scheduled.hour'			=> 'min:1,max:24',
											'expire.timeout.minute'			=> 'min:30,max:120',
										];


	/* ---------------------------------------------------------------------------- RELATIONSHIP ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- QUERY BUILDER ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- MUTATOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- ACCESSOR ----------------------------------------------------------------------------*/
	
	/* ---------------------------------------------------------------------------- FUNCTIONS ----------------------------------------------------------------------------*/
		
	/**
	 * boot
	 * observing model
	 *
	 */
	public static function boot() 
	{
        parent::boot();

		User::observe(new UserObserver);
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where email
	 *
	 * @param string or array of email
	 **/
	public function scopeEmail($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('email', $variable);
		}

		return $query->where('email', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where app type
	 *
	 * @param string or array of app type
	 **/
	public function scopeAppType($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('accesses.app.type', $variable);
		}

		return $query->where('accesses.app.type', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where app version
	 *
	 * @param string or array of app version
	 **/
	public function scopeAppVersion($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('accesses.app.version', $variable);
		}

		return $query->where('accesses.app.version', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where company code
	 *
	 * @param string or array of company code
	 **/
	public function scopeCompanyCode($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('accesses.company.code', $variable);
		}

		return $query->where('accesses.company.code', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where client id
	 *
	 * @param string or array of client id
	 **/
	public function scopeClientID($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('accesses.client_id', $variable);
		}

		return $query->where('accesses.client_id', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where accesses scope 
	 *
	 * @param string or array of accesses scope 
	 **/
	public function scopeAccessScopes($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('accesses.scopes', $variable);
		}

		return $query->where('accesses.scopes', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

}
