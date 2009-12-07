<div>
<img class="treeimg" src="images/channel.png" alt="C">

{if $Tree->c->description != ''}
	<span class="withcomment"><a href="#">
{/if}
{* Following must be one line because otherwise the comment-link will have a trailing whitespace *}
{$Tree->c->name}{if $Tree->c->description != ''}<span class="commenttext">{$Tree->c->description}</span></a></span>{/if}

{if $Tree->c->temporary != false}<i>(Temp)</i>{/if}
{if $Tree->c->description != ''}
	<img class="treeimg" src="images/comment.png" alt="(Comment)">
{/if}
{if $Tree->users|@count > 0}
	<blockquote class="treelist">
		<div>
		{foreach from=$Tree->users item=User}
			{assign var='Tpl__UserSession' value=$User->session}
			{if isset($UsersAddon.$Tpl__UserSession.isTalking) AND false!=$UsersAddon.$Tpl__UserSession.isTalking}
				<img class="treeimg" src="images/talking_on.png" alt="U">
			{else}
				<img class="treeimg" src="images/talking_off.png" alt="U">
			{/if}
			{if isset($UsersAddon.$Tpl__UserSession.InfoData) AND $UsersAddon.$Tpl__UserSession.InfoData|@count > 0}
			{* Following must be one line because otherwise the comment-link will have a trailing whitespace *}
				<span class="withcomment"><a href="#">{$User->name}<span class="commenttext">
				<span class="table">
				{foreach from=$UsersAddon.$Tpl__UserSession.InfoData key=Key item=Text}
					<span class="tr"><span class="td" style="padding-right:1em;">{$Key}</span><span class="td">{$Text}</span></span>
				{/foreach}
				</span>
				</span></a></span>
			{else}
				{$User->name}
			{/if}
			{if $User->userid > -1}
				<img class="treeimg" src="images/authenticated.png" alt="(Authed)">
			{/if}
			{if $User->mute}
				<img class="treeimg" src="images/muted_server.png" alt="(Server-Muted)">
			{/if}
			{if $User->suppress}
				<img class="treeimg" src="images/muted_suppressed.png" alt="(Suppressed)">
			{/if}
			{if $User->selfMute}
				<img class="treeimg" src="images/muted_self.png" alt="(Muted)">
			{/if}
			{if $User->deaf}
				<img class="treeimg" src="images/deafened_server.png" alt="(Server-Deafened)">
			{/if}
			{if $User->selfDeaf}
				<img class="treeimg" src="images/deafened_self.png" alt="(Deaf)">
			{/if}
			{if isset($UsersAddon.$Tpl__UserSession.InfoData.Comment) AND ''!=$UsersAddon.$Tpl__UserSession.InfoData.Comment}
				<img class="treeimg" src="images/comment.png" alt="(Comment)">
			{/if}
			<br>
		{/foreach}
		</div>
	</blockquote>
{/if}
{if $Tree->children|@count > 0}
	<blockquote class="treelist">
	{foreach from=$Tree->children item=NewTree}
		{include file='tree_recursion.tpl' Tree=$NewTree}
	{/foreach}
	</blockquote>
{/if}
</div>
