@layout('layouts.default')

@section('content')
<div class="row-fluid">
		<div class="span8">
			<div class="messages-in">
				@if($ifgetverb)
					@include('messages.ajaxrequest')
				@else
				<span style="display:block;margin-top:170px;text-align:center;font-size:2em;color:#ccc;">
					konuşma seçilmedi daha
				</span>
				@endif
			</div>
		</div>
		<div class="span4">
			<ul class="messageUlist">
			<!--foreasch falan -->
			@foreach ($cons as $item)
			{{-- Php function for trimming date --}}
			<?php
				$date = new DateTime($item->date);
				$newdate = $date->format('d.m.Y');
				if($newdate == date('d.m.Y')){
					$newdate = $date->format('H:i');
				}
			?>
			<li>
				<div class="clearfix">
					<a href="http://localhost/messages/{{$item->id}}" class="oneUser" rel="disabled" state="true" title="{{$item->Author->username}}">
						<div class="clearfix pvs">
							<div class="pull-left mr10">
								{{Gravitas\API::image($item->Author->user_email,50, $item->Author->username)}}
							</div>	
							<div class="topInform">
								<abbr class="timestamp pull-right" title="{{$item->date}}">{{$newdate}}</abbr>
								<div class="flow-el author">{{$item->Author->username}}</div>
								<p class="sub flow-el">{{$item->subject}}</p>  
							</div>

						</div>
					</a>
				</div>
			</li>
			@endforeach
		</ul>
		</div>
		<script type="text/javascript">
			$(document).pjax('.oneUser', '.messages-in')
		</script>
	</div>
@endsection