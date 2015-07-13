<div class="entry-atcall" data-highlight="{{$entry->count}}">
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