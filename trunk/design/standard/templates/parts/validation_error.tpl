{default $msg_list=array()}
{if $msg_list|count|ge(1)}
	<div class="validation-errors">
	{foreach $msg_list as $msg}
		{$msg}<br />
	{/foreach}
	</div>
{/if}