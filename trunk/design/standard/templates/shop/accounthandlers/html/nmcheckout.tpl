<div id="nmcheckout-order-view">

	<dl>
		<dt>
			{"Client"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{if $order.account_information.company|ne('')}
				{$order.account_information.company}
			{else}
				{$order.account_information.first_name} {$order.account_information.last_name}
			{/if}
		</dd>
		{if $order.account_information.company|ne('')}
		<dt>
			{"VAT no."|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{$order.account_information.vat_no}
		</dd>
		<dt>
			{"Contact"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{$order.account_information.first_name} {$order.account_information.last_name}
		</dd>
		{/if}
		{if and($order.account_information.phone, ne($order.account_information.phone, ''))}
		<dt>
			{"Phone"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{$order.account_information.phone}
		</dd>
		{/if}
		{if and($order.account_information.email, ne($order.account_information.email, ''))}
		<dt>
			{"Email"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			<a href="mailto:{$order.account_information.email}">{$order.account_information.email}</a>
		</dd>
		{/if}
	</dl>
	
	<dl class="last">
		{if $order.account_information.delivery|ne('')}
		<dt>
			{"Delivery"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{def $default_delivery_methods=ezini('General', 'DefaultDeliveryMethods', 'nmcheckout.ini')}
			{def $parts=array()}
			{foreach $default_delivery_methods as $delivery}
				{set $parts=$delivery|explode(';')}
				
				{if eq($parts.1, $order.account_information.delivery)}
					{$parts.0}
				{/if}
			{/foreach}
		</dd>
		{/if}
		{if $order.account_information.payment|ne('')}
		<dt>
			{"Payment"|i18n('checkout/accountview/html')}
		</dt>
		<dd>
			{$order.account_information.payment}
		</dd>
		{/if}
	</dl>
	
	
	<fieldset id="delivery-address">
		<legend>
			{if $order.account_information.invoice_as_delivery|eq('yes')}
				{"Delivery and invoice address"|i18n('checkout/accountview/html')}
			{else}
				{"Delivery address"|i18n('checkout/accountview/html')}
			{/if}
		</legend>
		{if $order.account_information.company|ne('')}
			{$order.account_information.company}<br />
			{"Att"|i18n('checkout/accountview/html')}: {$order.account_information.first_name} {$order.account_information.last_name}
		{else}
			{$order.account_information.first_name} {$order.account_information.last_name}
		{/if}<br />
		{if $order.account_information.delivery_street|ne('')}{$order.account_information.delivery_street}<br />{/if}
		{$order.account_information.delivery_zip} {$order.account_information.delivery_city}<br />
		{if $order.account_information.delivery_state|ne('')}{$order.account_information.delivery_state}<br />{/if}
		{if $order.account_information.delivery_country|ne('')}{$order.account_information.delivery_country}<br />{/if}
	</fieldset>
	
	{if $order.account_information.invoice_as_delivery|ne('yes')}
		{if or( $order.account_information.invoice_zip|ne(''),
				$order.account_information.invoice_city|ne('') )}
				
			<fieldset id="invoice-address">
				<legend>{"Invoice address"|i18n('checkout/accountview/html')}</legend>
				{if $order.account_information.company|ne('')}
					{$order.account_information.company}<br />
					{"Att"|i18n('checkout/accountview/html')}: {$order.account_information.first_name} {$order.account_information.last_name}
				{else}
					{$order.account_information.first_name} {$order.account_information.last_name}
				{/if}<br />
				{if $order.account_information.invoice_street|ne('')}{$order.account_information.invoice_street}<br />{/if}
				{$order.account_information.invoice_zip} {$order.account_information.invoice_city}<br />
				{if $order.account_information.invoice_state|ne('')}{$order.account_information.invoice_state}<br />{/if}
				{if $order.account_information.invoice_country|ne('')}{$order.account_information.invoice_country}<br />{/if}
			</fieldset>
		{/if}
	{/if}
	
	{if and($order.account_information.comment, $order.account_information.comment|ne(''))}
	<fieldset id="comment">
		<legend>{"Comment"|i18n('checkout/accountview/html')}</legend>
		{$order.account_information.comment|nl2br}
	</fieldset>
	{/if}
	
	<div class="clear"></div>
</div>