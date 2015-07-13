@layout('layouts.default')


@section('content')
	<div id="sendEntry">
   <div class="lead alert alert-info"><h4> {{Input::get('term')}}</h4></div>
  		{{ Form::open('thread/create','',array('id'=>'threadform')) }}
		{{ Form::textarea('inputarea', '', array('id' => 'input','class'=>'wmd-panel','placeholder' => 'bu lafın altında kalacağıma...')) }}
		<br>
		{{ Form::hidden('thread_name',$url)}}
		{{ Form::submit('gönder bro',array('class'=>'btn','id'=>'transmitter',))}}
		@render('layouts.modal')
    	{{ Form::close() }}
  	</div>
@endsection