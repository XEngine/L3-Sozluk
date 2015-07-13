<?php

class user_Controller extends Base_Controller {

	public $restful = true;
	public function __construct()
	{

	}
	public function get_index()
	{
		return Redirect::to('user/settings');
	}

	public function get_login(){
		$view = View::make('login');
		$view->title = 'Giriş';
		return $view;
	}
	public function get_register($data = ''){

			
			/*
			* If register not allowed.So Check Database	
			*/
			$regConfig = array('allow_register'=>'1','board_disable' => 0);
			 $captcha = '<script type="text/javascript"src="http://www.google.com/recaptcha/api/challenge?k=6LeA2t4SAAAAAHWbMfA3u6YOhjtJ3QSc7rvh-ZVm"></script><noscript><iframe src="http://www.google.com/recaptcha/api/noscript?k=6LeA2t4SAAAAAHWbMfA3u6YOhjtJ3QSc7rvh-ZVm" height="300" width="500" frameborder="0"></iframe><br> <textarea name="recaptcha_challenge_field" rows="3" cols="40">	</textarea> <input type="hidden" name="recaptcha_response_field" value="manual_challenge">  </noscript>';

			$checkDB = XConfig::getConfig($regConfig,true);
			if ( !$checkDB )
			{
				Redirect::to('user/register',200,$checkDB);
			}
		
			if ( Sentry::check())
			{
				return Redirect::to('/');
			}
			if ( $data == 'success')
			{
				$view = View::make('user.activation')->with('title','Aktivasyon Sayfasi');
				return $view;
			}
			$view = View::make('user.register');
			$view->title = "Avare Sözlük Kayıt";
			$view->captcha = $captcha;
			return $view;
	}
	public function get_settings($data = 'account')
	{
		if ( !Sentry::check())
		{
			return Redirect::to('user/login');
		}
		$dataPages = array('account','contacts','control');
		// Navtab isn't legal 
		if (empty($data) || !isset($data) ||!in_array($data,$dataPages)){
			$data = 'account';
		}
		
		$view = View::make('user.settings_'.$data);
		$view->title = "Kullanıcı Ayaları";

		return $view;
	}
	public function get_logout()
	{
		Sentry::logout();
   		return Redirect::to('/');
	}

	public function post_login()
	{
		  // get POST data
		$userdata = array(
		'username' => Input::get('username'),
		'password' => Input::get('password')
		);



		/*
			Control if already logged
		*/ 
		try
		{
			// log the user in
			$valid_login = Sentry::login($userdata['username'],$userdata['password'],true);
			if ($valid_login)
			{
				return Redirect::to('dashboard');
			}
			else
			{
				return Redirect::to('user/login')->with('login_errors', true);
			}
		}
		catch (Sentry\SentryException $e)
		{
			// issue logging in via Sentry - lets catch the sentry error thrown
			// store/set and display caught exceptions such as a suspended user with limit attempts feature.
			return $errors = $e->getMessage();
		}
	}
	public function post_register()
	{


		$rules = array(
			'regName' => 'required|max:100',
			'regUsername' => 'required|unique:users,username',
			'regPassword' => 'required|max:50|min:3',
			'regPassword2' => 'same:regPassword',
			'regEmail' => 'required|email|unique:users,user_email',
			'regDate' => 'required',
			'regGender' => 'required',
			
		    //'recaptcha_response_field' => 'recaptcha:6LeA2t4SAAAAAMS3kVU07dWb46T7_t_kd1QjpDLu'
		);

		$vld = Validator::make(Input::all(),$rules);

		/*
			User Types
			======================================
		   -3 => no-activation
		   -2 => no-post
		   -1 => banned
			0 => çaylak
			1 => yazar
			2 => gammaz
			3 => mod & gammaz
			4 => gammaz
			5 => admin
			6 => god
		*/
		if ($vld->valid())
		{
			
			$userdata = array(
				'name' => Input::get('regName'),
				'username' => Input::get('regUsername'),
				'password' => Hash::make(Input::get('regPassword')),
				'user_email' => Input::get('regEmail'),
				'user_birthdate' => Input::get('regDate'),
				'user_gender' => Input::get('regGender'),
				'user_type' => '0',
				'activation_hash' => Str::random(20),
			);
			/*
			Create user object instance and save data to database
			inşallah maşallah
			*/
			$user = User::create($userdata);
			if ($user)
			{	
				$response = array();

				$response[0] = "regUsername";
				$response[1] = true;
				$response[2] = "Kullanıcı Kaydı Başarılı";	
				$url = URL::base()."user/activation/".$user->activation_hash;
				$this->sendMail($user->user_email,'Aktivasyon Maili',"<a href='$url'>Aktivasyon İcin Lütfen Tıklayınız</a>");
				return Response::json($response);
			}
			else
			{
				 return Redirect::to('user/register',array('DB_ERROR' => 'Sanırım Database\'de bir sıkıntı var'));
			}

		}
		else
		{
			$response = array();

			$response[0] = "regUsername";
			$response[1] = false;
			$response[2] = $vld->errors->all(":message");

			return Response::json($response[2]);
		}

	}

	public function sendMail($email,$subject,$thread)
	{
		Message::to($email)
    ->from('px5x64@gmail.com', 'Avare Sozluk')
    ->subject($subject)
    ->body($thread)
    ->send();

    return Response::json(array('anan' =>'true'));
	}


	public function post_check()
	{
		$response = array();
		$response[1] = true;
		$response[0] = "regUsername";
		$response[2] = "regUsername";


		$formInfo = Input::all();
		$response = array();
		$response[0] = $formInfo['fieldId'];
		$value = $formInfo['fieldValue'];
		if ( $response[0] == "regUsername")
		{
			$user = User::where('username','=',$value)->first();

			if ($user != null)
			{
				$response[1] = false;
				$response[2] = "yok aga bu önceden alınmıs";
			}
			else
			{
				$response[1] = true;
				$response[2] = "evet bunla üye olabilirsin cınım";
			}
		}

		else if ( $response[0] == "regEmail")
		{
			$user = User::where('user_email','=',$value)->first();
			if ($user != null)
			{
				$response[1] = false;
				$response[2] = "yok aga bu önceden alınmıs";
			}
			else
			{
				$response[1] = true;
				$response[2] = "evet bunla üye olabilirsin cınım";
			}

		}

		return Response::json($response);	

	}
	public function get_activation($hash = null)
	{
		if ( $hash != null)
		{
			$user = User::where('activation_hash','=',$hash)->first();

			if ( $user != null)
			{
				$user->user_type = 1;
				$user->activated = '';
				$user->save();
				Redirect::to("user/login");

			}
			else
			{
				echo "yok böyle bişi";
			}
		}
	}
	
}