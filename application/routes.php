<?php


Route::get('entry/edit/(:num?)',array('before' => 'Sentry_auth',function($id)
{
	// check user is admin or mod ?
	$usrLVL = Auth::User()->user_type;
	$entry = Post::find($id);
	$entryType = 1;
	$found = false;

	/*
		Çaylak = 0;
		Normal = 1;
		Silik = -1;
		Gizli = 2;
		Sabit = 3;
	*/
	
	
	$view = View::make('entry.edit')->with('title','Entry Düzenle');
	if ( $entry)
	{		
		$view->found = true;
		$view->entry = $entry;
	}
	else
	{
		$view->found = false;
	}
	return $view;

}));
Route::get('entry/(:num?)','entry@id');
Route::get('entry/(:num?)/(:num?)','entry@at');
//Route::get('(.*)/(.*)/page/(.*)','entry@index');
Route::get('(.*)/(.*)','entry@index');
Route::get('(.*)','entry@index');
Route::post('entry','entry@post');
Route::get('messages','messages@index');
Route::get('messages/(:num?)','messages@conversation');
Route::get('moderator/check', 'moderator@checkperm');
Route::controller(Controller::detect());

/*
|--------------------------------------------------------------------------
| Route Listen Events
|--------------------------------------------------------------------------
*/
Event::listen('404', function()
{
	$page = URI::current();
	die('404***'.$page);
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
*/
Route::filter('before', function()
{
	#########################
	//STYLES - JAVASCRIPTS
	#########################
	Asset::container('general')->add('MainStyle', 'css/avare-v2.css')
	->add('jqueryui', 'js/jquery/jquery-ui-1.10.1.custom.min.js')
	->add('TheReactor', 'js/core.js');

	/*BootStrapper*/
	Asset::container('bootstrapper')->add('bsstle','css/bootstrap.min.css')
	->add('bsjvscript','js/bootstrap.js');
	/*Markdown Editör Script*/
	Asset::container('Markdown')->add('md.editor','js/jquery/jquery.markitup.js')
	->add('md.editorbb','js/sets/bbcode/set.js')
	->add('md.style','js/sets/bbcode/style.css');
	#########################
	//STYLES - JAVASCRIPTS
	#########################
	function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
	View::composer(array('leftsidebar'), function($view)
	{
		$query =  Thread::order_by('last_message_at', 'desc')->take(30)->get();
		$data = array();
		
		if ( Sentry::check() )
		{
			
			foreach($query as $title)
			{
				
				$tms = ThreadsMember::where('thread_id','=',$title->id)->where('user_id','=',Sentry::user()->id)->first();
				$x = array();
				if ( $tms != null )
				{
					$status = true;
					$x['mark_read'] = true;	
				}
				else
				{	
					$x['mark_read'] = false;
				}
				$arrayZ = $title->to_array();
				$arrayZ['mark_read'] = $x['mark_read'];
				$arrayZ['today_count'] = DB::only('select count(id) as ct from xr_posts where datetime > date_sub(now(), interval 1 day) and thread_id = '.$title->id);
			
				array_push($data,$arrayZ);

				
				
			}
			$view->threads = json_decode(json_encode($data), FALSE);
		}
		else
		{
			$view->threads = $query;
		}
		
		//print_r($data);
		//print_r($data);
		$view->top_message = "";
	});
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('/');
});
Route::filter('Sentry_auth', function()
{
	if (Sentry::guest()) return Redirect::to('/');
});

Route::filter('ip',function(){

    // Create the Eloquent object Visit
    $visit = new Track;

  	$browser  = new Browser;
    $visit->location = Locate::get( 'city' ) . ', ' . Locate::get( 'state' ) . ', ' . Locate::get( 'country' );
    $visit->ip_address = Request::ip();
   
    $visit->request = URI::current();
    if( Auth::check() )
        $visit->user_id = Auth::user()->id;

    // Browser stats 

    $visit->browser = $browser->getBrowser();
    $visit->browser_version = $browser->getVersion();
    $visit->platform = $browser->getPlatform();
    $visit->mobile = $browser->isMobile();
    $visit->robot = $browser->isRobot();

    
    $visit->save();
});