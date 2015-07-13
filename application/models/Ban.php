<?php 

class Ban extends Eloquent{


	public function user()
	{
		return $this->belongs_to('user');
	}
}