<div class="nm-checkout nm-checkout-register">
{include uri="design:parts/ez_box_top.tpl" step=3}
<h1>Registrer ordre</h1>

<div class="tooltip"></div> 

{* if errors exist *}
{if $validation|count|ge(1)}
<div class="message-error">
	<h2>{"Order data invalid"|i18n('checkout/register')}</h2>
	<p>{"Your order data did not fully validate. Please correct the errors below, and try again."|i18n('checkout/register')}</p>
</div>
{/if}
<form action="" method="post" id="register-order">
	{include uri="design:parts/register_form.tpl" data=$data validation_rules=$validation_rules}
	<div class="buttonblock">
		<input class="button" type="submit" name="CancelButton" value="{"Back"|i18n('checkout/register')}" />
		<input class="button" type="submit" name="StoreButton" value="{"Next"|i18n( 'checkout/register')}" />
		{if $redirect_set}
			<input type="hidden" name="RedirectURI" value="{$redirect_uri}" />
		{/if}
	</div>
</form>
{include uri="design:parts/ez_box_bottom.tpl"}
</div>