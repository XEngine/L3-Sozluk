<?php

class Dashboard_Controller extends Base_Controller {

	public function __construct(){
		$this->filter('before', 'Sentry_auth|ip');
	}
	
	public function action_index(){
		$view = View::make('dashboard');
		$view->user = Sentry::user();
		$view->title = 'Kullanıcı Paneli';
		$count = DB::table('threads')->where('last_message_at', '=', DB::Raw('NOW()'))->count();
		$view->count = $count;	
		return $view;


	}
}