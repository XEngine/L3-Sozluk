<?php
/**
 * Group Model
 */
class Group extends Eloquent
{
	/**
	 * Holds the current user group
	 * @var string
	 */
	protected static $_group = null;


	public static function make($identifier = '')
	{
		if ( ! empty($identifier))
		{
			// get the identifier type being used
			$identifier_field = static::_identifier_type($identifier);

			// let's try to find the use by using their identifier
			$group = static::where($identifier_field, '=', $identifier)->first();

			if ($group)
			{
				static::$_group = $group;
			}
			else
			{
				throw new AvareException(__('avareauth.user_does_not_exist'));
			}

			// return the user object
			return $group;
		}

		// die('here looks like identifier is empty');

		return new self();
	}


	/********************************************************************************
	 * Setup Relationships
	 * *****************************************************************************/

	/**
	 * User Relationship - Belongs to
	 * @return object returns the user object
	 */
	public function user()
	{
    	return $this->belongs_to('User');
	}

	/**
	 * Setup permissions relationship - has many and belongs to
	 * 
	 * @return Permission Eloquent Object
	 */
	public function permissions()
    {
        return $this->has_many_and_belongs_to('Permission');
    }


	/********************************************************************************
	 * CRUD Stuff :)
	 * *****************************************************************************/
	
	/**
	 * Create a New Group
	 * 
	 * @param  string  $name  name of the group we want to create
	 * @return Group object
	 */
	public function create_group($attributes = array())
	{
		// activation hash
		if  ( ! array_key_exists('name', $attributes) or empty($attributes['name']))
		{
			throw new AvareException(__('avareauth.no_group_data_provided'));
		}

		// check to see if the group already exists
		if ( $this->group_exists($attributes['name']))
		{
			throw new AvareException(__('avareauth.group_already_exists'));
		}

		// activation hash
		if (array_key_exists('parent', $attributes))
		{
			// check to see if the group already exists
			if ( ! $this->group_exists($attributes['parent']))
			{
				throw new AvareException(__('avareauth.parent_group_does_not_exist'));
			}
		}

		return $group = self::create($attributes);
	}

	/**
	 * Create a New Group
	 * 
	 * @param  string  $name  name of the group we want to create
	 * @return Group object
	 */
	public function update_group($attributes = array())
	{
		// make sure we have a user selected
		if ( ! static::$_group)
		{
			throw new AvareException(__('avareauth.no_group_selected'));
		}

		if (array_key_exists('name', $attributes))
		{
			static::$_group->name = trim($attributes['name']);

			// unset the email field since we no longer need it
			unset($attributes['name']);
		}

		if (array_key_exists('description', $attributes))
		{
			static::$_group->description = trim($attributes['description']);

			// unset the email field since we no longer need it
			unset($attributes['description']);
		}

		if (array_key_exists('parent', $attributes))
		{
			if ( $attributes['parent'] != false and ! $this->group_exists( (int) $attributes['parent']))
			{
				throw new AvareException('avareauth.parent_group_does_not_exist');	
			}

			static::$_group->parent = trim($attributes['parent']);

			// unset the email field since we no longer need it
			unset($attributes['parent']);
		}

		// update the user's
		return static::$_group->save();
	}


	/****************************************************************************************************
	 * PERMISSIONS
	 * *************************************************************************************************/

	/**
	 * Adds a user to a group
	 * 
	 * @param integer  $group_id  id of the group
	 *
	 * @return boolean
	 */
	public function add_permission($permission_id)
	{

		// make sure a user has been selected.
		if ( ! static::$_group)
		{
			throw new AvareException(__('avareauth.no_group_selected'));
		}

		// make sure we have a group id
		if (empty($permission_id))
		{
			throw new AvareException(__('avareauth.field_empty', array('Permissions ID')));
		}

		// check to see if the user is already in the group
		if ( $this->has_permission($permission_id))
		{
			// there is no need to kill it here, so just return true.
			return true;
		}

		return static::$_group->permissions()->attach($permission_id);
	}

	/**
	 * Removes a permission from a group
	 * @param  id  $permission_id  id of the permission to remove
	 * @return boolean
	 */
	public function remove_permission($permission_id)
	{
		// make sure a user has been selected.
		if ( ! static::$_group)
		{
			throw new AvareException(__('avareauth.no_group_selected'));
		}

		// make sure we have a group id
		if (empty($permission_id))
		{
			throw new AvareException(__('avareauth.field_empty', array('Permission ID')));
		}

		// check to see if the user is actually in the group
		if ( ! $this->has_permission($permission_id))
		{
			// there is no need to kill it here, so just return true.
			return true;
		}

		// $query =  static::find(static::$_group->id)->group()->where('id'get();
		$query = $this->permissions()->pivot()->where_group_id(static::$_group->id)->where_permission_id($permission_id)->delete();

		if ($query)
		{
			return true;
		}

		return false; 
	}

	/**
	 * Check to see if a group has a permission
	 * 
	 * @param  integer  $permission_id  id of the group
	 * 
	 * @return boolean
	 */
	public function has_permission($permission_id)
	{
		if ( ! static::$_group)
		{
			throw new AvareException(__('avareauth.no_group_selected'));
		}

		if (empty($permission_id))
		{
			throw new AvareException(__('avareauth.field_empty', array('Permission Id')));
		}

		// $query =  static::find(static::$_group->id)->group()->where('id'get();
		$query = $this->permissions()->where_group_id(static::$_group->id)->where_permission_id($permission_id)->first();

		if ($query and $query->count() > 0)
		{
			return true;
		}

		return false;
	}

	public function fetch_permissions()
	{
		if ( ! static::$_group)
		{
			throw new AvareException(__('avareauth.no_group_selected'));
		}

		$group_permissions = static::$_group->permissions()->get();

		foreach ($group_permissions as $val)
		{
			if ( ! in_array($val->resource, $permissions))
			{
				$permissions[] = $val->resource;
			}
		}

		return $permissions;
	}



	/****************************************************************************************************
	 * HELPER METHODS
	 * *************************************************************************************************/

    /**
	 * Checks to see if a group exists
	 * 
	 * @param  mixed  $identifier  group name or id
	 * 
	 * @return boolean
	 */
	public function group_exists($identifier)
	{
		// get the type of field we are looking for
		$field = $this->_identifier_type($identifier);

		// query the db
		$query = self::where($field, '=', $identifier);

		return $query->count() > 0;
	}

	/**
	 * decide what type of user identifier we are working with
	 * @param  mixed   $identifier either the user id, email address or username
	 * @return string  type of identifier
	 */
	protected static function _identifier_type($identifier)
	{
		if (filter_var($identifier, FILTER_VALIDATE_INT) !== false)
		{
			return 'id';
		}
		else
		{
			return 'name';
		}
	}
}