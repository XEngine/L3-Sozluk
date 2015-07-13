<?php
 //application/models/Thread.php
    class Message extends Eloquent {

    	public function author(){
    	  return $this->belongs_to('User', 'user_id');
    	}
    }
?>