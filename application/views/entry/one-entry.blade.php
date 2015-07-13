@layout('layouts.default')
@section('content')

<div id="entrybox">
	<div class="topicheader clearfix"><h2>{{$thread->title}}</h2></div>
	<div class="entryinsider">
			<div class="inside">
			<div class="entry clearfix" id="{{$entry->id}}">
				<div class="entrynumber" id="at{{$entry->count}}">{{$entry->count}}</div>
				<div class="entry_text">{{$entry->entry}}</div>
			</div><br/>
			<div align="right" class="edate">
				<span class="entry_user">{{$entry->author->username}}</span>
				<span class="entry_date">{{$entry->datetime}}</span>
				<span class="entry_date">#{{$entry->id}}</span>
			</div>
		</div>
	</div>

  	<div class="showall">
  		{{ HTML::link($thread->id.'/'.$thread->alias, 'tümünü görüntüle', array('title' => $thread->title,'state' => 'true')) }} 
  	</div>
</div>

@endsection