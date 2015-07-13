	/*Timeout Form functions*/
	$(function(){

		$('a').click(function(e){
			 str = $(this).attr('href');
			 var splitted = str.split(baseUrl)[1];
			 key = splitted.split("/");
			if ( key[1] == 'moderator' && key[2] != 'dashboard' )
			{
				e.preventDefault();
				console.log(key[1]);
				$('#entrylist').html('<p>buraya formları ajaxla cekicem Ajax Bind akar burda hadi gömdüm</p>'+$(this).html());
				$("#loading-overlay").fadeIn();
				$.ajax({
					url : str,
					type : 'get',
					data : '',
					success : function(j)
					{
						$('#entrylist').html(j);
						
						
					}
				});
				$("#loading-overlay").fadeOut();
				console.log(key[2]);
				switch(key[2])
				{
					case 'ban':
					bindBanForm();
					console.log("calisti");
					break;
					case 'edit':					
					break;
					default:
					//bindDashBoard();
					break;
				}
			}
			else
			{
				console.log('engellenmedi')
			}			
		});

		$('.error').fadeOut(4000);
		
		$('div[class="entry_text"]').dblclick(function(e){
			/*Thadius - Editable Entry Screen.*/

			if(permission)
			{
			    var divHtml = $(this).html(); // notice "this" instead of a specific #myDiv
			    var editableText = $("<textarea />");
			    editableText.html(divHtml);
			    $(this).replaceWith(editableText);
			    editableText.focus();
			    editableText.markItUp(mySettings);
			}
		});
 		
		$('#entryform').submit(function(e){
				MIN_POST_LENGTH = 2;
				val = $('textarea[name=inputarea]').val();
				if ( $.trim(val) == '' || val.length  < MIN_POST_LENGTH)
				{
					e.preventDefault();
					alert('boş ve sacma sapan entry gönderemeziniz.');		
				}			

			});
		$('input.regDate').datepicker({
			format : 'yyyy-mm-dd'
		});
		$('.user-pane').click(function(e){		
			$("#userPanel").slideToggle('slow');
		});
		$('form#settings_account').submit(function(e){
			
			var form = $(this).serialize();
			e.preventDefault();
			$.ajax({
				type : "POST",	
				dataType : 'json'	       ,
		        data: form,
				url : baseUrl+'/doajax/user/settings',
				success : function(j){
					switch(j.result)
					{
						case 'PASSWORD_CHANGED':
							htm = showMessage('success','Parola Gayet Başarılı Bir Şekilde Değiştirildi.');										
						break;
						case 'EMAIL_CHANGED':
							htm = showMessage('success','EMAIL DEĞİŞTİ');	
						break;	
						case 'PASSWORD_NOT_MATCH':
							htm = showMessage('warning','Parolalar uyuşmuyor.');
						break;
						case 'CURRENT_PASSWORD_WRONG':
							htm = showMessage('error','Şu anki şifreniz yanlış girildi.');
						break;
						default : 
							htm = showMessage('error','AMK Heryer Boş');
						break;
						
					}
					console.log(j.result);
					$("#settings_account").prepend(htm);
					$(".alert").delay(2000).fadeOut('slow');
				}
			});
		});
	 	$('form#sozReg').submit(function(e){
	 		
	 	});
	 	/*Ajax post send*/

	});
