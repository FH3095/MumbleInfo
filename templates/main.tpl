{include file='header.tpl'}
<body>
{if isset($LoginLink) && ''!=$LoginLink}
	<div style="float:right;"><a href="login.php">{$LoginLink}</a></div>
{/if}
{include file='tree.tpl'}
{$RawDebug}
</body>
{include file='footer.tpl'}
