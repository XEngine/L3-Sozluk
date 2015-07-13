@layout('layouts.default')

@section('content')
<div style="margin-left : 10px">
    {{ Form::open('user/login','post',array('class' => 'form-horizontal')) }}
     @if (Session::has('login_errors'))
	    <div class="alert alert-error">kullanıcı adı veya parola hatası.</div>	 
	 @endif
	    <h2>enter the matrix</h2>

   	 <fieldset>
		
	    <!-- check for login errors flash var -->
	   
		<br>
	   
	    <!-- username field -->
	    <p>{{Form::text('username', '',array('placeholder' => 'anan','class' => 'anan'))}}</p>
	    <!-- password field -->

	    <p>{{ Form::password('password') }}</p>
	    <!-- submit button -->
	    <label class="checkbox"> <input type="checkbox"> beni hatirla 	</label>  
	
		
		  <button type="submit" class="btn"><i class="icon-ok"></i>Gir</button>
		  
		
		 	 <button type="button" class="btn btn-link">şifremi unuttum</button>
	
		  <button type="button" class="btn btn-link">aktivasyon epostasi tekrar gönder</button>
		 


		
	  
	</fieldset>
    {{ Form::close() }}
    </div>
@endsection