@layout('layouts.default')

@section('content')
{{-- USER SETTINGS FORM--}}
	<div id="userSettings">

		<div id="navTab-container">
			<ul class="tabGroup">
				<li>{{ HTML::link('user/settings/account','Kullanıcı Ayarlari',array('class'=> 'here'))}}</li>
				<li>{{ HTML::link('user/settings/control','Sözlük Ayarlari') }}</li>
				<li>{{ HTML::link('user/settings/contacts','Sosyal Medya') }} </li> 

			</ul>			
		</div>
		<div id="settingsContact">		
			{{Form::open('user/settings/','post',array('id'=>'settings_account'))}} 					
				<p> {{ Form::text('e_username',Sentry::user()->username ,array('class' => 'e_username','readonly' =>''))}} </p>
				<p> {{ Form::label('e_password','Şifre : ') }} </p>
				<p> {{ Form::password('e_password',array('class' => 'e_password'))}} </p>
				<p> {{ Form::label('re_password','Şifre Tekrar : ') }} </p>
				<p> {{ Form::password('re_password',array('class' => 'e_password'))}} </p>	
				<p> {{ Form::label('e_email','Email : ') }} </p>
				<p> {{ Form::text('e_email',Sentry::user()->user_email,array('class' => 'e_email'))}} </p>
				<p> {{ Form::label('e_currentPass','Şu anki Şifreniz.')}} </p>
				<p> {{ Form::text('e_currentPass','',array('class' => 'e_currentPass'))}} </p>
				<p> {{Form::submit('gonder')}}
			{{Form::close()}}
		</div>
	</div>
 
@endsection