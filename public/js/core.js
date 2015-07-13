var l = window.location;
var baseUrl = "http://localhost/avare/public/";
var body = $(document.body);


	function getCommentedEntry(e)
	{
		var threadid = $(this).data('thread');
		var at = $(this).data('at');
		var rel = $(this).attr("rel");
		var disabled = $(this).attr("disabled");
		var thies = $(this);
		var parent = $(e.target).closest("div").parent().parent();
		var created_at = parent.find("[data-highlight='"+at+"']");
		if (rel === 'at-tag' && created_at.length == 0 && disabled !== "disabled")
		{
			$(this).attr('disabled','disabled');
			$.ajax({
				url: baseUrl+'/entry/'+threadid+'/'+at,
				type : "GET",
				timeout : 3000,
				success : function(data) 
    			{
    				parent.prepend(data);
    				this.removeAttr("disabled");
    			}
			});//End ajax call
		}
		if(created_at.length > 0)
		{
			created_at.effect("highlight", {}, 780);
		}
		e.preventDefault();
	}

	function formProcess(e)
	{
 		var main,el,currentURL,page,URLWithoutPage,html;
 		main = $(this);

		
		//Page variables
		currentURL = location.pathname.split('/');
		params = get_urlParameters();
		if(jQuery.inArray('page',params) != -1)
		{
			page = params['page'];
		}else{
			page = 0
		}
		URLWithoutPage = baseUrl+currentURL[0]+"/"+currentURL[1]+"/"+currentURL[2];


		//Serialize our form submission data
		var data = JSON.stringify(main.serializeObject());

		//Todo : Validations before sending the post.
		//disable the submit button before post process.
		submitToggle($("input:submit",main));
		//We are sending the post
		$.ajax({url:baseUrl+"/entry/",type:'POST',cache:false,data:{json: data},success:postsuccess,dataType:"json"});
		function postsuccess(data){
			if(data.success === 0)
			{
				$('#coreModal').modal({content: data.msg,header:'Hata!'});
				submitToggle($("input:submit",main),0);

				return true;
			}

			html = '<div style="display:none" class="inside"><div class="entry clearfix" id="'+data.id+'"><div class="entrynumber" id="at'+data.count+'">'+data.count+'</div><div class="entry_text">'+data.entry+'</div></div><br/><div align="right" class="edate"><span class="entry_user">'+data.author+' </span><span class="entry_date">'+data.date+' </span><a href="http://localhost/entry/'+data.id+'" class="entry_date">#'+data.id+'</a></div></div>';
			el = $(html);
			if(page != data.page){
				scrollToAppend(el,$('.entryinsider'),data.page,1);
				submitToggle($("input:submit",main),0);	
			}else{	
				scrollToAppend(el,$('.entryinsider'),data.page);
				submitToggle($("input:submit",main),0);
			}
			$("textarea#input").val(" ");
		}
		//Preventing the default action
		e.preventDefault();
	}


/*########################################################################*/
/*########################### HELPER FUNCTIONS ###########################*/

	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	}
	//>>mami bu fonksyonu ben bile anlamıyorum ama ben yazdım. çöz yiyosa amk :D
	function scrollToAppend(obj,container,page,sep)
	{
		sep = (typeof sep == 'undefined')?0:sep;
		var objID = $('.entry',obj).attr('id');
		if(sep)
		{
			var seperator = '<div ref="'+page+'" class="inside-page yui3-pjax"><a href="'+get_threadURL()+'?page='+page+'&highlight='+objID	+'">.....</a></div>';
			if (!$(".inside-page")[0]){
				container.append(seperator); 
				$(".inside-page").hide().slideDown({duration: 500});
			}
			var lastSeperator = $(".inside-page").last();

			if(lastSeperator.attr('ref') != page){
				container.append(seperator);
				lastSeperator.hide().slideDown({duration: 500});
			}
			if($(".inside-page:last").length > 0)
			{

				$(".inside-page:last a").attr('href',get_threadURL()+'?page='+page+'&highlight='+objID);
			}
			container.append(obj);
			obj.hide();
			obj.slideDown({duration: 1000, easing: "easeInElastic"}).effect("highlight", 780);
			return true;
		}

		container.append(obj);
		obj.slideDown({duration: 1000, easing: "easeInElastic"}).effect("highlight", {}, 780);
	}

	function submitToggle(obj,bool)
	{
		bool = (typeof bool == 'undefined')?1:bool;
		if(bool)
		{
			obj.attr('disabled', 'disabled');
		}else{
			obj.removeAttr('disabled');
		}
		return false;
	}
	function getEntryHeader()
	{
		if ($('.topicheader h2').length > 0) {
			return $('.topicheader h2').html();
		}
	}
	function get_threadURL()
	{
		currentURL = location.pathname.split('/');
		url = baseUrl+currentURL[0]+"/"+currentURL[1]+"/"+currentURL[2];
		return url.toString();	
	}
	function get_urlParameters(){
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}
	function showLoading (int)
	{
		if (int == 1){
			$("#loading-overlay").fadeIn();
		}else if(int == 0)
		{
			$("#loading-overlay").fadeOut();
		}
	}
	function highLight(){
		var param = get_urlParameters();
		if(param.length > 0){
			if(jQuery.inArray('highlight',param) != -1){
				var el = $("div[data-id='"+param['highlight']+"']");
				if(el.length > 0){
					$('html,body').animate({scrollTop: el.offset().top -100}, 700,function(){
						el.effect("highlight", 780);
					});	
				}
			}
		}
	}
	function editorPush() {
      $('#input').markItUp(mySettings);
	}
	/*@Event Listeners*/
	$(document).ajaxSend(function(event, request, settings) {
		showLoading(1);
	});
	$(document).ajaxComplete(function(event,request, settings) {
		showLoading(0);
	});
	$(document).on('submit','#entryform',formProcess);
	$(document).on('click','a[data-thread]',getCommentedEntry);

	$("input[name=term]").keydown(function(event){
            thread = $("input[name=term]").val();
			$("ul.ui-autocomplete").fadeIn("slow");
	});
	$('input[name=term]').keypress(function(e){
	
	});
	$(document).ready(function() {
      highLight();
        $(".sozReg").validationEngine({
        		ajaxFormValidation: true,
				ajaxFormValidationMethod: 'post',
				onAjaxFormComplete: checkForm,
			});
      $('#input').markItUp(mySettings);
      $('.regDate').datepicker( {
      	showButtonPanel: true,
      	dateFormat: 'yy-mm-dd',
  		changeMonth: true,
     	changeYear: true,
	    yearRange: "-50:+0",
      });
      $('input[name=term]').autocomplete({
 		 source: baseUrl+'search/thread/',
 		  cacheLength: 0,
 		 response: function(event,data,test){
 		 
 		 },
      	 select: function( event, ui ) {
      	 	window.location = baseUrl+ui.item.id 

      	},

      




	});

	});
	String.prototype.replaceAt = function(index, char) {
    return this.substr(0, index) + "<span style='font-weight:bold;color:#454545;'>" + char + "</span>";
	}

    $.ui.autocomplete.prototype._renderItem = function(ul, item) {
    this.term = this.term.toLowerCase();
    var resultStr = item.label.toLowerCase();
    var t = "";
    while (resultStr.indexOf(this.term) != -1) {
        var index = resultStr.indexOf(this.term);
        t = t + item.label.replaceAt(index, item.label.slice(index, index + this.term.length));
        resultStr = resultStr.substr(index + this.term.length);
        item.label = item.label.substr(index + this.term.length);
    }
    return $("<li style='background-color:#fafafa;'></li>").data("item.autocomplete", item).append("<a>" + t + item.label + "</a>").appendTo(ul);
	}
	function checkForm(status, form, json, options){
	
		if ( json[1] == true)
		{
			alert("Kayit Basarılı");
		}       
		else
		{
			alert("Yuh artik database de hata");
		}
	}
	YUI().use('pjax', function (Y) {
		Y.Pjax.defaultRoute.push(editorPush);
   		var pjax = new Y.Pjax({container: '#entrylist',titleSelector:'h2'});
   		pjax.on('load',function (e){
   			 $('#input').markItUp(mySettings);
   		});
	});