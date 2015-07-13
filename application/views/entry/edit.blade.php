@layout('layouts.default')
@section('content')
@if ($found)
{{Form::open('entry/last/123')}}
{{Form::close()}}
@else
<p>Bulunamadı</p>
@endif

@endsection