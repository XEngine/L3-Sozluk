<?php 
	/**
	* 
	*/
	class DoAjax_Controller extends Base_Controller
	{
		 
		public $restful = true;


		public function post_check()
		{
			if ( Request::ajax())
			{
				echo "true";
			}
			else
			{
				echo "";
			}
		}
	}

 ?>