@layout('layouts.default')

@section('content')
{{-- USER SETTINGS FORM--}}
	<div id="userSettings">

		
		<div id="navTab-container">
			<ul class="tabGroup">
				<li>{{ HTML::link('user/settings/account','Kullanıcı Ayarlari')}}</li>
				<li>{{ HTML::link('user/settings/control','Sözlük Ayarlari') }}</a> </li>
				<li>{{ HTML::link('user/settings/contacts','Sosyal Medya',array('class'=> 'here')) }} </li> 

			</ul>			
		</div>
		<div id="settingsContact">		
			{{Form::open('user/settings/','post',array('id'=>'user_settings'))}}

			<p>{{Gravitas\API::image(Sentry::user()->user_email, 128, Sentry::user()->name)}}</p>
			 <p> {{Form::file('avatar') }}</p>
			 <p>{{Form::text('twitter','',array('placeholder' => 'buraya twitter'))}}</p>
			 <p>{{Form::text('facebook','',array('placeholder' => 'buraya facebook'))}}</p>
			 <p>{{Form::text('youtube','',array('placeholder' => 'buraya youtube'))}}</p>
			 <p>{{Form::text('foursquare','',array('placeholder' => 'buraya foursquare'))}}</p>
			 <p></p>
			 <p></p>
			{{Form::close()}}
		</div>
	</div>
 
@endsection 