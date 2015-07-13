@layout('layouts.default')


@section('leftsidebar')
<div id="left-sidebar">
<div class="elist">
	<ul>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/news','haber ekle')}}</li>
		<li>{{HTML::link('moderator/apply','kullanıcı onayla')}}</li>
		<li>{{HTML::link('moderator/checkstats','istatistikleri yenile')}}</li>
		<li>{{HTML::link('moderator/topicrise','başlık canlandır')}}</li>
		<li>{{HTML::link('moderator/browse','şikayet değerlendir')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
		<li>{{HTML::link('moderator/ban','kullanıcı yasakla')}}</li>
	</ul>
</div>
</div>
@endsection

@section('content')
<p>Haberler</p>
@endsection