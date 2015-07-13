<div id="formHolder">
	<div id="formReal">
		{{Form::open('','post',array('id' => 'banUser'))}}
		{{Form::label('bUsername','Kullanıcı Adı')}}
		{{Form::text('bUsername','',array('class' => 'required','placeholder' => 'Kullanıcı Adı'))}}
		{{Form::label('bBanTime','Ban Süresi')}}
		{{Form::select('bBanTime',array('a' => '1 gün','b' => '3 gün','c' => '1 hafta','d' => '1 ay','e' => '3 ay','f' => 'sınırsız' ))}}

		{{Form::label('bReason','Sebeb ?')}}
		{{Form::textarea('bReason','',array('placeholder' => 'sepeb giriniz.'))}}
	</br>
		{{Form::submit('Banla')}}
		{{Form::close()}}
	</div>	
	<div id="userInfo">
		<span class='UI-username'>Timeout</span>
		<span class='UI-history'>34 Ceza Puanı</span>
		<span class='UI-email'>m.arikaya&copy;yahoo.com</span>
	</div>

	<script type="text/javascript">

		$('#banUser').validate();
	</script>

</div>

