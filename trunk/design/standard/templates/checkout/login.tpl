<div class="nm-checkout nm-checkout-login">
{include uri="design:parts/ez_box_top.tpl" step=2}
<form class="checkout-box" action={"checkout/login"|ezurl} method="post" id="existing-client">
	<fieldset>
		<legend>{"Existing client"|i18n('checkout/login')}</legend>
		<div class="error">
			{if $loginerror}
				{'The email or/and password was wrong, please try again.'|i18n('checkout/login')}
			{/if}
		</div>
		<ol class="forms">
			<li>
				<label for="email">{"E-mail"|i18n('checkout/login')}</label>
				<input type="text" name="Login" id="email" class="box" />
			</li>
			<li>
				<label for="password">{"Password"|i18n('checkout/login')}</label>
				<input type="password" name="Password" id="password" class="box small" />
			</li>
		</ol>
		
		<p><a href={"/user/forgotpassword"|ezurl}>{"Forgot your password?"|i18n('checkout/login')}</a></p>
		
		<input type="hidden" value="{$redirect_uri}" name="RedirectURI"/>
		
	</fieldset>
	<div class="buttonblock">
		<input type="submit" value="{"Log in"|i18n('checkout/login')}" name="LoginButton" class="button" />
	</div>
</form>

{* fetch web shop user groups *}
{def $parent_node=fetch('content', 'node', hash('node_id', ezini('WebShopUser', 'ParentShopUserGroupNodeID', 'nmcheckout.ini')))}
{def $user_group_list=fetch('content', 'list', hash( 	'parent_node_id', $parent_node.node_id, 
		            									'class_filter_type', 'include', 
											            'class_filter_array', array('user_group'), 
											            'sort_by', $parent_node.sort_array))}

<form class="checkout-box" action={"checkout/register"|ezurl} method="post">
	<fieldset>
		<legend>{"New client"|i18n('checkout/login')}</legend>
		
		<ol class="forms">
			<li class="grouping"><label>{"Choose client type"|i18n('checkout/login')}</label>
				<ul>
					{foreach $user_group_list as $user_group}
						<li><input type="radio" name="client_type" id="user_group_{$user_group.node_id}" value="{$user_group.node_id}" /><label for="info1">{$user_group.name}</label></li>
					{/foreach}
				</ul>
			</li>
		</ol>
	</fieldset>
	<div class="buttonblock">
		<input type="submit" value="{"Register"|i18n('checkout/login')}" class="button" />
		{if $redirect_register_uri_set}
			<input type="hidden" name="RedirectURI" value="{$redirect_register_uri}" />
		{/if}
	</div>
</form>

{include uri="design:parts/ez_box_bottom.tpl"}
</div>