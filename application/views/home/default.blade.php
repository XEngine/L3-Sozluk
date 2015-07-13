<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-language" content="{S_USER_LANG}" />
<meta http-equiv="imagetoolbar" content="no" />
<link rel="shortcut icon" HREF="{T_THEME_PATH}/images/fav.ico">
<title>{{$title}}</title>	
<!-- JS MAINFRAME -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="{T_THEME_PATH}/js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="./assets/js/ajax.js"></script> 
<script type="text/javascript" src="{T_THEME_PATH}/js/jquery.fancybox.pack.js"></script>
<!-- pushState Ajax.js --> 
<script type="text/javascript" src="{T_THEME_PATH}/js/jquery.pjax.js"></script>


<!-- CSS STYLES CORE -->
<link rel="stylesheet" href="{T_THEME_PATH}/jquery.fancybox.css" type="text/css" media="screen" />
<link rel='stylesheet' type='text/css' href='{T_THEME_PATH}/avare-v2.css' />

</head>
<body>
	<div id="wrapper">
    {{ $content }}
	</div>
</body>
</html>