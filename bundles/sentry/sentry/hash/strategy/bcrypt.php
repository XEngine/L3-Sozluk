<?php
/**
 * Part of the Sentry bundle for Laravel.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Sentry
 * @version    1.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */

namespace Sentry;

use Str;

/**
 * BCrypt Hashing Driver
 */
class Sentry_Hash_Strategy_BCrypt extends Sentry_Hash_Driver
{

	public function __construct($options)
	{
		if ( ! isset($options['hashing_algorithm']))
		{
			$options['hashing_algorithm'] = null;
		}

		if ( ! isset($options['strength']) or $options['strength'] < 4 or $options['strength'] > 31)
		{
			$options['strength'] = 8;
		}

		if ( ! isset($options['laravel_hash']))
		{
			$options['laravel_hash'] = true;
		}
		$this->options = $options;
	}

	public function create_password($password)
	{
		$rounds = 8;
		$work = str_pad($rounds, 2, '0', STR_PAD_LEFT);

		// Bcrypt expects the salt to be 22 base64 encoded characters including
		// dots and slashes. We will get rid of the plus signs included in the
		// base64 data and replace them with dots.
		if (function_exists('openssl_random_pseudo_bytes'))
		{
			$salt = openssl_random_pseudo_bytes(16);
		}
		else
		{
			$salt = Str::random(40);
		}

		$salt = substr(strtr(base64_encode($salt), '+', '.'), 0 , 22);

		return crypt($password, '$2a$'.$work.'$'.$salt);
	}

	public function check_password($password, $hashed_password)
	{
		if ($this->options['hashing_algorithm'] == 'hash')
		{
			$salt = substr($hashed_password, 0, 16);

			$password = $salt.$this->hash_password($password, $salt);

			return $password == $hashed_password;
		}
		if ($this->options['laravel_hash'])
		{
			return crypt($password, $hashed_password) === $hashed_password;
		}
		$strength = substr($hashed_password, 4, 2);

		return (substr($hashed_password, 0, 60) === crypt($password, "$2a$".$strength."$".substr($hashed_password, 60)));
	}

	protected function hash_password($password, $salt)
	{
		if ($this->options['hashing_algorithm'])
		{
			return $salt.hash($this->options['hashing_algorithm'], $salt.$password);
		}

		$strength = $this->options['strength'];

		return crypt($password, sprintf('$2a$%02d$', $strength).$salt).$salt;

	}

}
