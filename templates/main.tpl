{include file='header.tpl'}
<body>
{if isset($NoServer) && true==$NoServer}
	<div>There is no such server.</div>
{else}
	{include file='tree_recursion.tpl' Tree=$Tree}
{/if}
{if isset($ServerVersion) && ""!=$ServerVersion}
<div>Server version: {$ServerVersion}</div>
{/if}
{$RawDebug}
</body>
{include file='footer.tpl'}
