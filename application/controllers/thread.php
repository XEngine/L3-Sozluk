<?php 

class Thread_Controller extends Base_Controller{
	public $restful = true;

	public function get_create()
	{

		// check if thread exists

		$alias = Str::slug(Input::get('term', 'avare'));
		$thread = Thread::where('alias','=',$alias)->first();

		if ( $thread != null)
		{
			return Redirect::to(URL::base().$thread->id);
		}
		$title = "Baslık aç";
		
		$view = View::make('thread.create');
		$view->url = Input::get('term', 'avare');
		$view->title = $title;

		return $view;
	}

	public function post_create()
	{
		$posts = Input::all();

		$title = $posts['thread_name'];
		$contentRaw = $posts['inputarea'];

		if ( $title != '' && strlen($contentRaw) > 10)
		{
			$alias = Str::slug($title,'-');
			$exist = Thread::where('alias','=',$alias)->first();

			if ( $exist != null)
			{
				return Redirect::to($exist->id);
			}
			
			$threadData = array
			(
				'title' => $posts['thread_name'],
				'alias' => $alias,
				'type'  => 0,
				'poster_ip' => Request::ip(),
				'dateline' =>  date ("Y-m-d H:i:s"),
				'last_message_at' =>  date ("Y-m-d H:i:s")

			);
			$thread = Thread::create($threadData);
			if ( $thread != null)
			{
				$content = static::replace_at(BBCode2Html(strip_tags_attributes($contentRaw)),$thread->id);

				$postData = array(
					'thread_id' => $thread->id,
			        'entry' => $content,
					'userip' => Request::ip(),
					'user_id' => Sentry::user()->id,
					'datetime' => date ("Y-m-d H:i:s"),
					'count' => 1,
					'type' => 0,
				);

				$pst = Post::create($postData);

				if ( $pst != null){
					return Redirect::to($thread->id);
				}
			}

		} 
		else
		{
			return Redirect::to(URL::full());
		}
	}

	/*HELPER FUNCTIONS*/
	protected static function replace_at($str,$threadid){
		$str = preg_replace('/@(\d+)/', '<a href="#" rel="at-tag" data-thread='.$threadid.' data-at="$1">@$1</a>',$str);
		return $str;
	}
}