<div class="elist">
	<ul>
	@foreach ($threads as $thread)	
	<li>
	@if ( Sentry::check() != null) 
		@if ( Sentry::user()->has_access('is_mod') )
		<a title="Moderator actions" id="thread_mod_{{$thread->id}}" class="ModMenu" href="mod/action/{{$thread->id}}">&nbsp;</a>
		@endif
	@endif
	
	@if ( $thread->mark_read)
		<a class="yui3-pjax" id="{{$thread->id}}" href="{{URL::to($thread->id.'/'.$thread->alias)}}">{{$thread->title}}<span style="margin-left : 10px;" class="badge badge-warning">{{$thread->today_count}}</span></a>
	@else
		<a class="yui3-pjax" id="{{$thread->id}}" href="{{URL::to($thread->id.'/'.$thread->alias)}}">{{$thread->title}}<span style="margin-left : 10px;" class="badge badge">{{$thread->today_count}}</span></a>
	@endif


	</li>
	@endforeach
	</ul>

</div>