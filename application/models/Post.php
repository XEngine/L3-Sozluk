<?php
class post extends Eloquent {
	public static$timestamps = false;

 public function author()
  {
      return $this->belongs_to('User', 'user_id');
  }

}
?>