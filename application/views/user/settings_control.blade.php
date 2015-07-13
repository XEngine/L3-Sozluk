@layout('layouts.default')

@section('content')
{{-- USER SETTINGS FORM--}}
	<div id="userSettings">

		<div id="navTab-container">
			<ul class="tabGroup">
				<li>{{ HTML::link('user/settings/account','Kullanıcı Ayarlari')}}</li>
				<li>{{ HTML::link('user/settings/control','Sözlük Ayarlari',array('class' =>'here')) }}</a> </li>
				<li>{{ HTML::link('user/settings/contacts','Sosyal Medya') }} </li> 

			</ul>			
		</div>
		<div id="settingsContact">		
			 {{ Form::open('','post',array('id'=>'settings_control')) }} 
			 {{ Form::text('email', null, array('class' => 'input-small', 'placeholder' => 'Email'))  }}
			 {{ Form::password('pass', array('class' => 'input-small', 'placeholder' => 'Password'))  }}

			 {{ Form::submit('Sign in') }}
			 {{ Form::close() }}
		</div>
	</div>
 
@endsection