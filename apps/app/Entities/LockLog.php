<?php

namespace App\Entities;

use App\Entities\Observers\LockLogObserver;

/**
 * Used for LockLog Models
 * 
 * @author cmooy
 */
class LockLog extends BaseModel
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $collection			= 'service_lock_logs';

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
											'key'							,
											'padlock'						,
											'pandora'						,
											'owner'							,
											'parent'						,
											'next'							,
										];
										
	/**
	 * Basic rule of database
	 *
	 * @var array
	 */
	protected $rules				=	[
											'key'						=> 'required|max:255',
											'padlock'					=> 'required|max:255',
											'pandora._id'				=> 'required|max:255',
											'pandora.type'				=> 'required|max:255',
											'pandora.field'				=> 'max:255',
											'owner._id'					=> 'required',
											'owner.type'				=> 'in:person,organization',
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

		LockLog::observe(new LockLogObserver);
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
	 * scope to get condition where padlock
	 *
	 * @param string or array of padlock
	 **/
	public function scopePadlock($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('padlock', $variable);
		}

		return $query->where('padlock', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where owner
	 *
	 * @param string or array of owner
	 **/
	public function scopeOwnerID($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('owner._id', $variable);
		}

		return $query->where('owner._id', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where pandora
	 *
	 * @param string or array of pandora
	 **/
	public function scopePandoraID($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('pandora._id', $variable);
		}

		return $query->where('pandora._id', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where pandora
	 *
	 * @param string or array of pandora
	 **/
	public function scopePandoraType($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('pandora.type', $variable);
		}

		return $query->where('pandora.type', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where parent
	 *
	 * @param string or array of parent
	 **/
	public function scopeParent($query, $variable)
	{
		if(is_array($variable))
		{
			return 	$query->whereIn('parent', $variable);
		}

		return $query->where('parent', 'regexp', '/^'. preg_quote($variable) .'$/i');
	}

	/**
	 * scope to get condition where there is no next list
	 *
	 * @param string or array of there is no next list
	 **/
	public function scopeNoNext($query, $variable)
	{
		return $query->wherenull('next');
	}
}
