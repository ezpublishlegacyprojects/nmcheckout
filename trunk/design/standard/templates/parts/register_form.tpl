{* fetch current user *}
{def $current_user=fetch( 'user', 'current_user' )}

{* fetch default form fields *}
{def $default_form_fields=ezini('General', 'DefaultFormFields', 'nmcheckout.ini')}

{* fetch form fields that should be excluded from the selected client type *}
{def $group_name=concat('ClientGroup_', $client_type)}
{if ezini_hasvariable( $group_name, 'ExcludeFormFields', 'nmcheckout.ini' )}
	{def $exclude_form_fields=ezini($group_name, 'ExcludeFormFields', 'nmcheckout.ini')}
{else}
	{def $exclude_form_fields=array()}
{/if}

{def $form_field_name=''}
{def $label_text=''}

<input type="hidden" name="client_type" value="{$client_type}" />
<fieldset>
	<legend>{"Contact information"|i18n('checkout/register')}</legend>
	<ol class="forms">
		
		{set $form_field_name='company'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Company"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='vat_no'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			<label for="{$form_field_name}">{"VAT no."|i18n('checkout/register')}</label>
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='first_name'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
				{* if the company field is included *}
				{if and($default_form_fields|contains('company'), eq($exclude_form_fields|contains('company'), false()))}
				{set $label_text="Contact first name"|i18n('checkout/register')}
				{else}
				{set $label_text="First name"|i18n('checkout/register')}
				{/if}
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label=$label_text form_field_name=$form_field_name}
			
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='last_name'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
				{* if the company field is included *}
				{if and($default_form_fields|contains('company'), eq($exclude_form_fields|contains('company'), false()))}
				{set $label_text="Contact last name"|i18n('checkout/register')}
				{else}
				{set $label_text="Last name"|i18n('checkout/register')}
				{/if}
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label=$label_text form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='phone'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Phone"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box small" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='email'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="E-mail"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box tip" value="{$data.$form_field_name}" title="{"We'll send a copy of the order to this email address."|i18n('checkout/register')}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{* if the user is logged in, no use in asking for a password *}
		{if eq($current_user.is_logged_in, false())}
		{set $form_field_name='password'}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Password"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="password" name="{$form_field_name}" id="{$form_field_name}" class="box small tip" value="{$data.$form_field_name}" title="{"Please provide a password to make it easier to shop with us again."|i18n('checkout/register')}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{set $form_field_name='password_confirm'}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Confirm password"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="password" name="{$form_field_name}" id="{$form_field_name}" class="box small tip" value="{$data.$form_field_name}" title="{"Repeat your password to ensure that you've spelled it correctly."|i18n('checkout/register')}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
	</ol>
</fieldset>
<div class="nm-holder">
<fieldset id="delivery-address">
	<legend>{"Delivery address"|i18n('checkout/register')}</legend>
	<ol class="forms">
	
		{set $form_field_name='delivery_street'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Street"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		{set $form_field_name='delivery_street2'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label='Adresse' form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		{set $form_field_name='delivery_zip'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="ZIP"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box extra-small" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='delivery_city'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="City"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box small" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		
		{set $form_field_name='delivery_state'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="State"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
		{set $form_field_name='delivery_country'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Country"|i18n('checkout/register') form_field_name=$form_field_name}
			{include uri="design:parts/country_list.tpl" id=$form_field_name value=ezini('General', 'DefaultCountry', 'nmcheckout.ini')}
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
	</ol>
</fieldset>

{if or(
		and($default_form_fields|contains('invoice_street'), eq($exclude_form_fields|contains('invoice_street'), false())),
		and($default_form_fields|contains('invoice_zip'), eq($exclude_form_fields|contains('invoice_zip'), false())), 
		and($default_form_fields|contains('invoice_city'), eq($exclude_form_fields|contains('invoice_city'), false())), 
		and($default_form_fields|contains('invoice_state'), eq($exclude_form_fields|contains('invoice_state'), false())), 
		and($default_form_fields|contains('invoice_country'), eq($exclude_form_fields|contains('invoice_country'), false())) 
)}
<fieldset id="invoice-address">
	<legend>{"Invoice address"|i18n('checkout/register')}</legend>
	<input type="checkbox" id="invoice_as_delivery" name="invoice_as_delivery" value="yes" checked="checked" />
	<label for="invoice_as_delivery" class="inline">{"Same as delivery address"|i18n('checkout/register')}</label>
	<ol class="forms">
	
		{set $form_field_name='invoice_street'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Street"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		{set $form_field_name='invoice_street2'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Street"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		{set $form_field_name='invoice_zip'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="ZIP"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box extra-small" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
	
		{set $form_field_name='invoice_city'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="City"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box small" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
	
		{set $form_field_name='invoice_state'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="State"|i18n('checkout/register') form_field_name=$form_field_name}
			<input type="text" name="{$form_field_name}" id="{$form_field_name}" class="box" value="{$data.$form_field_name}" />
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
	
		{set $form_field_name='invoice_country'}
		{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
		<li>
			{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Country"|i18n('checkout/register') form_field_name=$form_field_name}
			{include uri="design:parts/country_list.tpl" id=$form_field_name value=ezini('General', 'DefaultCountry', 'nmcheckout.ini')}
			{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
		</li>
		{/if}
		
	</ol>
</fieldset>
{else}
<input type="hidden" id="invoice_as_delivery" name="invoice_as_delivery" value="yes" />
{/if}
</div> <!-- holder end -->
{* fetch default delivery methods *}
{def $default_delivery_methods=ezini('General', 'DefaultDeliveryMethods', 'nmcheckout.ini')}

{* fetch delivery methods that should be excluded from the selected client type *}
{def $group_name=concat('ClientGroup_', $client_type)}
{if ezini_hasvariable( $group_name, 'ExcludeDeliveryMethods', 'nmcheckout.ini' )}
	{def $exclude_delivery_methods=ezini($group_name, 'ExcludeDeliveryMethods', 'nmcheckout.ini')}
{else}
	{def $exclude_delivery_methods=array()}
{/if}
	
{set $form_field_name='delivery'}
{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
<fieldset id="delivery">
	<legend>{"Delivery"|i18n('checkout/register')}</legend>
	<ol class="forms">
		<li class="grouping">{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Delivery method"|i18n('checkout/register') form_field_name=$form_field_name}
			<ul>
				{foreach $default_delivery_methods as $delivery}
					
					{def $delivery_parts=$delivery|explode(';')}
					{if eq($exclude_delivery_methods|contains($delivery_parts.1), false())}
						<li><input type="radio" name="{$form_field_name}" value="{$delivery_parts.1}" id="delivery-method-{$delivery_parts.1}"{if eq($data.$form_field_name, $delivery_parts.1)} checked="checked"{/if} /><label for="info1">{$delivery_parts.0}</label></li>
					{/if}
				{/foreach}
			</ul>
		</li>
	</ol>
	{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
</fieldset>
{/if}

{* fetch default payment methods *}
{def $default_payment_methods=ezini('General', 'DefaultPaymentMethods', 'nmcheckout.ini')}

{* fetch payment methods that should be excluded from the selected client type *}
{def $group_name=concat('ClientGroup_', $client_type)}
{if ezini_hasvariable( $group_name, 'ExcludePaymentMethods', 'nmcheckout.ini' )}
	{def $exclude_payment_methods=ezini($group_name, 'ExcludePaymentMethods', 'nmcheckout.ini')}
{else}
	{def $exclude_payment_methods=array()}
{/if}
	
{* if at least one payment method exists *}
{if $default_payment_methods|count|ge(1)}

	{set $form_field_name='payment'}
	{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
	<fieldset id="payment">
		<legend>{"Payment"|i18n('checkout/register')}</legend>
		<ol class="forms">
			<li class="grouping">{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Payment method"|i18n('checkout/register') form_field_name=$form_field_name}
				<ul>
				{foreach $default_payment_methods as $payment}
					
					{def $payment_parts=$payment|explode(';')}
					{if eq($exclude_payment_methods|contains($payment_parts.1), false())}
						<li><input type="radio" name="{$form_field_name}" value="{$payment_parts.1}" id="delivery-method-{$payment_parts.1}"{if eq($data.$form_field_name, $delivery_parts.1)} checked="checked"{/if} /><label for="info1">{$payment_parts.0}</label></li>
					{/if}
				{/foreach}
				</ul>
			</li>
		</ol>
		{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
	</fieldset>
	{/if}
{/if}

{* if a comment can be specified *}
{set $form_field_name='comment'}
{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
	<fieldset id="comment">
		<legend>{"Comment"|i18n('checkout/register')}</legend>
		<ol class="forms">
			<li>
				{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="Comment"|i18n('checkout/register') form_field_name=$form_field_name}
				<textarea cols="10" rows="5" name="{$form_field_name}" id="{$form_field_name}" class="box">{$data.$form_field_name}</textarea>
				{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
			</li>
		</ol>
	</fieldset>
{/if}

{* if terms are active *}
{set $form_field_name='terms'}
{if and($default_form_fields|contains($form_field_name), eq($exclude_form_fields|contains($form_field_name), false()))}
	<fieldset id="shopping-terms">
		<legend>{"Terms"|i18n('checkout/register')}</legend>
		{def $term_node=fetch( 'content', 'node', hash( 'node_id', ezini( 'General', 'TermsNodeID', 'nmcheckout.ini' ) ) )}
		{def $term_data_map_identificator=ezini( 'General', 'TermsDataMapIdentificator', 'nmcheckout.ini' )}

		<div id="terms_text">{attribute_view_gui attribute=$term_node.data_map.$term_data_map_identificator}</div>
		<ol class="forms">
			<li>
				<input type="checkbox" name="{$form_field_name}" id="{$form_field_name}" class="checkbox" value="1" {if eq( $data.$form_field_name, '1' )}checked="checked"{/if} />
				{include uri="design:parts/label.tpl" validation_rules=$validation_rules.$form_field_name label="I accept the terms"|i18n('checkout/register') form_field_name=$form_field_name}
				{include uri="design:parts/validation_error.tpl" msg_list=$validation.$form_field_name}
			</li>
		</ol>
	</fieldset>
{/if}

