<?php

namespace App\Entities;

use App\Entities\Observers\ClientObserver;

/**
 * Used for Client Models
 * 
 * @author cmooy
 */
class Client extends BaseModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection			= 'mt_clients';

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
	protected $hidden 				= [];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable				=	[
											'app'							,
											'company'						,
											'key'							,
											'secret'						,
											'grants'						,
											'expire'						,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'app.type'						=> 'required|in:mobile,web',
											'app.version'					=> 'required|max:255',
											'app.name'						=> 'required|max:255',
											'company.code'					=> 'required|max:255',
											'company.name'					=> 'required|max:255',
											'key'							=> 'required|max:255',
											'secret'						=> 'required|max:255',
											'grants.*.name'					=> 'required|in:client_credential,password',
											'grants.*.scopes.*'				=> 'required|max:255',
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

		Client::observe(new ClientObserver);
    }

	/* ---------------------------------------------------------------------------- SCOPES ----------------------------------------------------------------------------*/

	/**
	 * scope to get condition where key
	 *
	 * @param string or array of key
	 **/
	public function scopeKey($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('key', $variable);
		}

		return $query->where('key', 'regexp', '/^'. preg_quote($variable) .'$/i');
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
			return 	$query->whereIn('app.type', $variable);
		}

		return $query->where('app.type', 'regexp', '/^'. preg_quote($variable) .'$/i');
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
			return 	$query->whereIn('app.version', $variable);
		}

		return $query->where('app.version', 'regexp', '/^'. preg_quote($variable) .'$/i');
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
			return 	$query->whereIn('company.code', $variable);
		}

		return $query->where('company.code', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where grant name
	 *
	 * @param string or array of grant name
	 **/
	public function scopeGrantName($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('grants.name', $variable);
		}

		return $query->where('grants.name', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where grant name
	 *
	 * @param string or array of grant name
	 **/
	public function scopeGrantScopes($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('grants.scopes', $variable);
		}

		return $query->where('grants.scopes', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}
}
