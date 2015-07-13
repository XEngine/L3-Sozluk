<?php

class ThreadsMember extends eloquent{
	public static $timestamps = false;
		public function user()
	{
		return $this->has_one('user');
	}
	public function thread()
	{
		return $this->has_one('thread');
	}
}