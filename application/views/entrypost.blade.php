  	<div id="sendEntry">
  		{{ Form::open('entry/post','',array('id'=>'entryform')) }}
		{{ Form::textarea('inputarea', '', array('id' => 'input','class'=>'wmd-panel','placeholder' => 'bu lafın altında kalacağıma...')) }}
		<br>
		{{ Form::hidden('thread_id',$threadinfo->id)}}
		{{ Form::submit('gönder bro',array('class'=>'btn','id'=>'transmitter',))}}
		@render('layouts.modal')
    	{{ Form::close() }}
  	</div>