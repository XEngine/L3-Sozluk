<?php 
	class Messages_Controller extends Base_Controller{

		public $restful=true;

		public function __construct()
		{
			$this->filter('before', 'Sentry_auth');
		}
		public function get_index()
		{
			if(Request::ajax()) {
				return '<span style="display:block;margin-top:170px;text-align:center;font-size:2em;color:#ccc;">
					konuşma seçilmedi daha
				</span>';
			}
			//This gets conversation that user started or we started..
			$conversation = Conversation::with('Author')
			->where('receiver_id','=',Sentry::user()->id)
			->or_where(function($query){
				$query->where('user_id','=',Sentry::user()->id);
			})
			->order_by('date','desc')
			->get();

			$view = View::make('messages.index');

			$view->cons = $conversation;
			$view->ifgetverb = false;
			$view->title = "Mesajlar";
			return $view;
		}
		public function get_conversation($conHash){
			$ajaxRequest = false;
			$thread = false;
			$numbered = false;
			if(Request::ajax()) {
				$ajaxRequest = true;	
			}
			$userid = Sentry::user()->id;
			/*There should be some protection about sniffing other messages*/
			
			/*Let check if this conversation exist*/
			$conThread = Conversation::where('id','=',$conHash)->first();
			if(!$conThread)
			{
				return Redirect::to_action('messages@index');
			}elseif($conThread->user_id != $userid && $conThread->receiver_id != $userid){
				/*now we are looking if this conversation is ours*/
				return Redirect::to_action('messages@index');
			}


			$conversation = Message::with('author')
			->where('conversation_id','=',$conHash)
			->order_by('date','asc')
			->get();
			if(!$ajaxRequest)
			{
				//This gets conversation that user started or we started..
				$conversation2 = Conversation::with('Author')
				->where('receiver_id','=',Sentry::user()->id)
				->or_where(function($query){
					$query->where('user_id','=',Sentry::user()->id);
				})
				->order_by('date','desc')
				->get();

				$view = View::make('messages.index');

				$view->cons = $conversation2;
				$view->ifgetverb = true;
				$view->title = "Mesajlar";
			    $view->conversation = $conversation;
			    $view->coninfo = $conThread;
			    return $view;
			}else{
			    $view = View::make('messages.ajaxrequest');
			    $view->conversation = $conversation;
			    $view->coninfo = $conThread;
			    return $view;
		    }
		}
		public function post_index()
		{
			//grabbing conversation
		}

	}


 ?>