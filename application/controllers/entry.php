<?php

class Entry_Controller extends Base_Controller {

	public $restful = true;
	public static $per_page = 15;
	public function __construct()
	{
		$this->filter('before', 'ip');
	}

	public function get_index($threadID = '',$threadAlias = '',$page = 1){
		$ajaxRequest = false;
		$thread = false;
		$numbered = false;

		if(Request::ajax()) {
			$ajaxRequest = true;	
		}
		
		//we need to check the threadID.
		if(is_numeric($threadID))	//this is only valid if url is example.com/entry/11111111/....
		{
			$threadLookup = Thread::where("id",'=',$threadID)->first();


			if ($threadLookup)
			{
				$thread = true; //we got it right... //we got at least ONE thread!
				if(empty($threadAlias)){
					return Redirect::to($threadLookup->id.'/'.$threadLookup->alias);
				}else{
					//we dont have to do anything if we have /id/alias/ schema.
				}
			}
		}
		/*
		* In above if condition corrects if there is no alias given to URL.
		* Above also correts the URL and returns it as corrected way.
		*/
	

		if(!$thread) //if this is false, we couldnt get the ID, so maybe user search the thread as raw. "ex.com/entry/bla bla"
		{
			$threadLookup = Thread::where('title', '=', $threadID)->first();
			if($threadLookup)
			{
				/*
				We got the thread by looking at its raw format. "bla bla bla".
				This is usually false. browsers dont like spaces and special characters
				So we need to put them to a true format and redirect it.
				*/
				return Redirect::to($threadLookup->id.'/'.$threadLookup->alias);
			}
			$threadLookup = Thread::where('alias', '=', $threadID)->first();
			if($threadLookup)
			{
				/*
				We got the thread by looking at its alias format. "bla-bla-bla".
				This is usually false. Because we also need ID
				So we need to put them to a true format and redirect it.
				*/
				return Redirect::to($threadLookup->id.'/'.$threadLookup->alias);
			}
			/*
			if we are still going on with !$thread that means there is no thread! so
			do the suggestion!

			TODO :: Suggestion!
			*/
		}
		if ( Sentry::check())
		{
			$tms = ThreadsMember::where('thread_id','=',$threadID)->where('user_id','=',Sentry::user()->id)->first();
			if ( $tms == null)
			ThreadsMember::create(array('thread_id' => $threadID,'user_id' => Sentry::user()->id,'read_flag' => 1));
		}

		$total = Post::where('thread_id','=',$threadID)->count();

		$Query = Post::with('author')
		->where('thread_id','=',$threadID)
		->order_by('id','asc')
		->paginate(static::$per_page);


		print_r($threadLookup);

		$paginator =  Paginator::make($Query, $total, static::$per_page);
		if($ajaxRequest){ //json returns if we get ajax request
		    $view = View::make('entry.entryajax');
		    $view->title = $threadLookup->title;
		    $view->threadinfo = $threadLookup;
			$view->posts = $Query;
			$view->paginate =$paginator;
		 	return $view;
		}else{
		    $view = View::make('entry.entry');
		    $view->title = $threadLookup->title;
		    $view->threadinfo = $threadLookup;
			$view->posts = $Query;
			$view->paginate = $paginator;
		 	return $view;
		}
	}
	public function post_post(){
		$data = json_decode(Input::get('json'));
		$fields = array(
			'post' => $data->inputarea,
			'threadid' => $data->thread_id,
		);
		if(Sentry::guest()){
			return json_encode(array("success" => 0,"msg" => "üye olda gel"));
		}
		//filter falan ama sonra ..
		$userid = Sentry::user()->id;
		$userip = Request::ip();
		$cThread = Thread::where('id','=',$fields['threadid'])->first();

		/*FLOOD PROTECTION*/
		####################
			/*$_messageTime = Post::where(function ($query) use ($cThread,$userid){
					$query->where('thread_id','=',$cThread->id);
					$query->where('user_id', '=',$userid);
			})
			->order_by('datetime','DESC')
			->first(array('datetime'));
			if($_messageTime){
				$_timestamp = strtotime($_messageTime->datetime);
				$_timeCalc = time()-10;
				if($_timestamp >= $_timeCalc)
				{
					return json_encode(array("success" => 0,"msg" => "Cok hızlı giriyorsun babacan!"));
				}
			}*/
		####################
		/*FLOOD PROTECTION*/


		// Check user has 10 post if he newbie member

		if ( Sentry::user()->user_type == 0)
		{
			$fulled = false;
			$post = Post::where('user_id','=',Sentry::user()->id);


			if ( $post->count() >= 10 )
			{
				return json_encode(array("success" => 0,"msg" => "Çaylak Olarak Bu kadar Yazdıgınız Yeter.\nLütfen Bir adminin onaylamasını bekleyiniz." ));
			}
			
		}
		if (Sentry::user()->has_access('can_post') && $cThread->type == 0 )
		{
			if ( Sentry::user()->user_type == 0)
			{
				$post_type = 0;
			}
			else
			{
				$post_type = 1;
			}

			if ( strlen(trim($fields['post'])) >= 5 || Sentry::user()->has_access('is_mod')  )
			{
				$max = Post::where('thread_id','=',$fields['threadid'])->max('count');
				$post = static::replace_at(BBCode2Html(strip_tags_attributes($fields['post'])),$fields['threadid']);
				$postData = array(
					'thread_id' => $fields['threadid'],
			        'entry' => $post,
					'userip' => $userip,
					'user_id' => $userid,
					'datetime' => date ("Y-m-d H:i:s"),
					'count' => $max+1,
					'type' => $post_type,
				);
				/*
					Update last message on thread table
				*/
				
				$cThread->last_message_at = date("Y-m-d H:i:s");
				$cThread->save();

				

				$id = DB::table('posts')->insert_get_id($postData);

				$entry = Post::with('author')->where_id($id)->first();
				$threadid=$fields['threadid'];
				$count = Post::where(function ($query) use ($threadid){
					$query->where('thread_id','=',$threadid);
					$query->where('type', '=',1);
				})
				->count();


				// cache deki konuyu okumuş memberlari sil
				DB::query('DELETE FROM xr_threadsmembers WHERE thread_id=?',array($threadid));
				
				/*Page Function*/
				$pagenum = ceil($count/static::$per_page);
				//doing ajax callbacks
		
				//create view
				$view = array(
					"id"	=>	$entry->id,
					"count"	=>	$entry->count,
					"entry"	=>	$entry->entry,
					"author" =>	$entry->author->username,
					"date"	=>	$entry->datetime,
					"page"	=>	$pagenum
				);
		 		return Response::json($view);
			}
			else{
				return json_encode(array("success" => 0,"msg" => "entry çok kısa babacan"));
			}
		}
		else
		{
			return json_encode(array("success" => 0,"msg" => "yetki yok hocam"));
		}
		return json_encode(array("success" => 0,"msg" => "Undefined Error!"));
	}

	public function post_postpreview(){
		return BBCode2Html(Input::get('data'));
	}
	public function get_id($id = 0){
		
		if (!empty($id)) {
			$entry = Post::with('author')->where_id($id)->first();
		}
		/*Getting the thread*/
		$thread = Thread::find($entry->thread_id);

		if(Request::ajax()){ 
		    $view = View::make('entry.one-entryajax');
			$view->entry = $entry;
			$view->thread = $thread;
		 	return $view;
		}else{
		    $view = View::make('entry.one-entry');
		    $view->title = '#'.$entry->id.' '.substr(strip_tags(trim(strtolower($entry->entry))), 0, 20). " ... ";
			$view->entry = $entry;
			$view->thread = $thread;
		 	return $view;
		}
	}
	public function get_at($id = 0,$at = 0){
		if(Request::ajax()){ 
			$entry = Post::with('author')->where(function ($query) use ($id,$at){
				$query->where('thread_id','=',$id);
				$query->where('count', '=',$at);
			})
			->first();
			/* low level security checks */
			if(!$entry) return false; //unassigned id?


		    $view = View::make('entry.one-entrypost');
			$view->entry = $entry;
		 	return $view;
		}
		return false;
	}
	/*HELPER FUNCTIONS*/
	protected static function replace_at($str,$threadid){
		$str = preg_replace('/@(\d+)/', '<a href="#" rel="at-tag" data-thread='.$threadid.' data-at="$1">@$1</a>',$str);
		return $str;
	}


}