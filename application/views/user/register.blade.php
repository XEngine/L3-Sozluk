@layout('layouts.default')

@section('content')	
	<div id="regForm">
	
	<fieldset>
		

		{{-- If not allowed To Register or Board is offline --}}
		@if ( isset($checkDB))
		{{$checkDB}}
		@else
		@endif
			{{Form::open('user/register','post',array('id' => 'sozReg','class' => 'sozReg'))}}

			</p>
				{{Form::label('regName','Ad - Soyad')}}
				{{Form::text('regName',null,array('placeholder' => 'ad-soyad','class' =>'regName validate[required,minSize[10],maxSize[90]]'))}} 
			</p>
			</p>
				{{Form::label('regUsername','kullanıcı adı')}}
				{{Form::text('regUsername',null,array('placeholder' => 'kullanıcı adı','class' =>'regUsername validate[ajax[checkUsername],required,minSize[3],maxSize[90]]'))}} 
			</p>
			</p>
				{{Form::label('regPassword','Parola')}}
				{{Form::password('regPassword',array('placeholder' => 'şifre','class' =>'regPassword validate[required,maxSize[90]]'))}}
			 </p>
 			</p>
				{{Form::label('regPassword2','Parola')}}
				{{Form::password('regPassword2',array('placeholder' => 'şifre tekrar','class' =>'regPassword validate[equals[regPassword],required,maxSize[90]]'))}}
			 </p>

			</p> 
				{{Form::label('regEmail','Email')}}
				{{Form::text('regEmail',null,array('placeholder' => 'email yazınız','class' => 'regEmail validate[ajax[checkUsername],required,custom[email],maxSize[90]]'))}} 
			</p>
			</p> 
				{{Form::label('regGender','Cinsiyet')}}
				{{Form::select('regGender',array(''=> 'Cinsiyet','m' => 'erkek','f' => 'kız','n' => 'belli değil'),array('class' => 'regGender validate[required]'))}}
			</p>
			<p>
				{{Form::label('regDate','Doğum Tarihi')}}
				{{Form::text('regDate','',array('class'=>'regDate','placeholder' => 'Doğum Tarihi'))}}
			</p>
			<p>
				{{$captcha}}
			</p>			
				{{Form::submit('Gönder',array('class' => 'btn btn-info'))}}			
		{{Form::close()}} 
		</fieldset>
	</div>
@endsection