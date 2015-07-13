<?php
    class Conversation extends Eloquent {

		public function getMessages()
	  	{
	  		return $this->has_many('Message');
	  	}
	  	public function Author()
	  	{
	  		return $this->belongs_to('User','user_id');
	  	}


    }
?>