<?php 


/**
* 
*/
class Moderator_Controller extends Base_Controller
{
	public  $restful  = true;
	public function __construct()
	{
		$this->filter('before','Sentry');
	}

	public function get_dashboard($value='')
	{
	
		$view = View::make('mod.dashboard')->with('title','Moderator Kontrol Paneli');
		return $view;
	}
	public function get_ban()
	{
	
		$view = View::make('mod.ban')->with('title','Kullanıcı yasakla');
		return $view;
		
	}
	public function post_ban()
	{
		if (Request::ajax())
		{	
			$error = '';
			$get = Input::all();
			$banUsername = $get['bUsername'];
			$banReason = $get['bReason'];

			$user = User::where('username','=',$banUsername)->first();

			// belki kullanıcı zaten banlı olabilir.admin değğilse tekrar banlayamasın


			if( $user->username == Sentry::user()->username)
			{
				$error = 'Lütfen kendinizi banlamayınız.';
				die("ban");
			}


			$ban = $user->bans()->first();
			// Kullanıcı Zaten Banlıysa,bitiş tarihini geri döndür.
			if ($ban)
			{
				die($ban->ban_end);
			}
			// Güvenlik Koaaaaaaaaaaaaaaaaaaaaaaaaantrolü

		
			$date= date('Y-m-d H:i:s');
			$bandate = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($date)));
			$banData = Ban::create(
			array(
				'user_id'      => $user->id,
				'ban_ip'          => $user->ip_address,
				'ban_email'       => $user->user_email,
				'ban_start'       => $date,
				'ban_end'         => $bandate,
				'ban_reason'      => $banReason,
				'ban_moderatorid' => Sentry::user()->id,
				)
				);
			die("OK");
	
		

			

	


		}

	}
	public function get_checkperm()
	{
		if (Sentry::user()->has_access('can_edit'))
		{
			return json_encode(array('result' => true));
		}else{
			return json_encode(array('result' => false));
		}
	}
}