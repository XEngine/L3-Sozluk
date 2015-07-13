@layout('layouts.default')
@section('content')

<div id="entrybox">
	<div class="topicheader clearfix">
		<h2>{{$threadinfo->title}}</h2>
		<div class="pagination-block">{{$paginate->links(1)}}</div></div>
	<div class="entryinsider">


	    @if (Session::has('message'))
	    <span class="error">{{session::get('message')}}</span>
	    @endif
		@foreach($posts->results as $post)
			<div data-id="{{$post->id}}" class="inside">
			<div class="entry clearfix">
				<div class="entrynumber" id="at{{$post->count}}">{{$post->count}}</div>
				<div class="entry_text">{{$post->entry}}</div>
			</div><br/>
			<div align="right" class="edate">
				<span class="entry_user">{{$post->author->username}}</span>
				<span class="entry_date">{{$post->datetime}}</span>
				{{ HTML::link('entry/'.$post->id, '#'.$post->id, array('class' => 'entry_date', 'title' => $post->author->username,'state' => 'true')) }}
			</div>
		</div>
		@endforeach
	</div>
	{{--ENTRY BOX--}} 
	@include('entrypost')
  	{{-- ENTRY BOX --}}

</div>

@endsection