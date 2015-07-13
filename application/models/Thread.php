<?php
 //application/models/Thread.php
    class Thread extends Eloquent {
     public static $table = 'threads';
     public static $timestamps = false;
     public function posts()
     {
          return $this->has_many('Post');
     }

    }
?>