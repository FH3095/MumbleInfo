{include file='header.tpl'}
<body>
<form method="POST" action="{$SELF}">
<input type="text" name="username">
<input type="password" name="password">
<input type="hidden" name="post_data" value="1">
<input type="submit" value="Einloggen">
</form>
</body>
{include file='footer.tpl'}
