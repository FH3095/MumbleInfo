{if isset($NoServer) && true==$NoServer}
	<div>There is no such server.</div>
{else}
	{include file='tree_recursion.tpl'}
{/if}
{if isset($ServerVersion) && ""!=$ServerVersion}
<div>Server version: {$ServerVersion}</div>
{/if}
