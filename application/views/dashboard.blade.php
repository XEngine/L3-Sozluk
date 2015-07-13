@layout('layouts.default')

@section('content')
<div id="entrybox">
<h2>Hoşgeldin, {{$user->name}} Efendi. Bu ne sürpriz!</h2>
<p class="entry"><span class="entry_text">Sözlükte sabahtan beri <strong>{{$count}}</strong> entry girilmiş haberin olsun moruk.</span></p>
<hr />
<br />

</div>
@endsection