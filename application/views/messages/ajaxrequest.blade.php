<ul class="messengerBody">
<?php
$datebool = true;
$__date = null;
?>
@foreach($conversation as $item)

{{-- Date Functions --}}
<?php
		
	$date = date_tr('d F Y',$item->date);
	$onlydate = date_tr('d.m.Y',$item->date);
	$onlytime = date_tr('H:i',$item->date);

	if($__date != $date){
		$datebool = true;
	}
	if($datebool)
	{
		echo '<li class="datetime">
			<abbr class="timestamp">konuşma başlangıcı: '.$date.'</abbr>
		    </li>';
		$__date = $date;
		$datebool = false;
	}

?>


<li class="messengerEntries">
	<div class="clearfix">
		<div class="pull-left mr10">
			{{Gravitas\API::image($item->author->user_email,32, $item->author->username)}}
		</div>
		<div class="clearfix">
			<div class="pull-right">
				<abbr class="timestamp" title="{{$date}}">{{$onlytime}}</abbr>
			</div>
			<div>
				<strong>{{$item->author->username}}</strong>
				<div>{{$item->message}}</div>
			</div>
		</div>
	</div>
</li>
@endforeach
</ul>
<script language="javascript">
$(document).ready(function()	{
    $('#input').markItUp(mySettings);
});
</script>
{{--ENTRY BOX--}} 
<div id="sendEntry">
	{{ Form::open('entry/post') }}
{{ Form::textarea('inputarea', '', array('id' => 'input','class'=>'wmd-panel','placeholder' => 'bu lafın altında kalacağıma...')) }}
<br>
{{ Form::hidden('thread_id',$coninfo->id)}}
{{ Form::submit('gönder bro',array('id'=>'transmitter'))}}
{{ Form::close() }}
</div>
{{-- ENTRY BOX --}}