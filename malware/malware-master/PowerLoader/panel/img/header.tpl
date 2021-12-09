<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>PowerLoader v1.0</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="icon" type="image/x-icon" href="favicon.ico" />
        <link href="img/general.css" rel="stylesheet" type="text/css" />

        <script language="javascript" src="img/ajax.js"></script>
        <script language="javascript" src="img/tasks.js"></script>
        <script src="img/jquery-1.2.3.pack.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(function() {
            $('#aSelectAll').click(function(event) {
                a = $('[id^=fi]:not(:checked)');
                b = $('[id^=fi]:checked');
                a.attr('checked', 'checked');
                b.removeAttr('checked');
                event.preventDefault();
            });
        });
        </script>
    </head>

<body>

<div id="top_menu">
    <div style="position: relative; left: 50%; top: 12px; text-align: left; margin-left: -512px; width: 1024px">
		<span><a href="?act=stats">Stats</a></span>
        <span><a href="?act=tasks">Tasks</a></span>
        <span><a href="?act=files">Files</a></span>
        <span><a href="?act=settings">Settings</a></span>
	</div>
</div>

<div id="main_container">

