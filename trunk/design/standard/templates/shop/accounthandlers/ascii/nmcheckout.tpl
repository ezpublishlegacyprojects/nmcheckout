
1. {"Order"|i18n('checkout/accountview/text')|upcase}
=======================================================

{"Client"|i18n('checkout/accountview/text')}: {if $order.account_information.company|ne('')}{$order.account_information.company}{else}{$order.account_information.first_name} {$order.account_information.last_name}{/if}


{if $order.account_information.company|ne('')}{"VAT no."|i18n('checkout/accountview/text')}: {$order.account_information.vat_no}
{"Contact"|i18n('checkout/accountview/text')}: {$order.account_information.first_name} {$order.account_information.last_name}{/if}


{if and($order.account_information.phone, ne($order.account_information.phone, ''))}{"Phone"|i18n('checkout/accountview/text')}: {$order.account_information.phone}
{/if}

{if and($order.account_information.email, ne($order.account_information.email, ''))}{"Email"|i18n('checkout/accountview/text')}: {$order.account_information.email}
{/if}


{if $order.account_information.delivery|ne('')}{"Delivery"|i18n('checkout/accountview/text')}: {def $default_delivery_methods=ezini('General', 'DefaultDeliveryMethods', 'nmcheckout.ini')}{foreach $default_delivery_methods as $delivery}{def $parts=$delivery|explode(';')}{if eq($parts.1, $order.account_information.delivery)}{$parts.0}{/if}{/foreach}{/if}

{if $order.account_information.payment|ne('')}{"Payment"|i18n('checkout/accountview/text')}: {$order.account_information.payment}{/if}



2. {if $order.account_information.invoice_as_delivery|eq('yes')}{"Delivery and invoice address"|i18n('checkout/accountview/text')|upcase}{else}{"Delivery address"|i18n('checkout/accountview/html')|upcase}{/if}

=======================================================

{if $order.account_information.company|ne('')}
{$order.account_information.company}
{"Att"|i18n('checkout/accountview/html')}: {$order.account_information.first_name} {$order.account_information.last_name}
{else}
{$order.account_information.first_name} {$order.account_information.last_name}
{/if}


{if $order.account_information.delivery_street|ne('')}{$order.account_information.delivery_street}{/if}

{$order.account_information.delivery_zip} {$order.account_information.delivery_city}

{if $order.account_information.delivery_state|ne('')}{$order.account_information.delivery_state}{/if}

{if $order.account_information.delivery_country|ne('')}{$order.account_information.delivery_country}{/if}



{if $order.account_information.invoice_as_delivery|ne('yes')}
3. {"Invoice address"|i18n('checkout/accountview/text')|upcase}
=======================================================

{if $order.account_information.company|ne('')}
{$order.account_information.company}
{"Att"|i18n('checkout/accountview/html')}: {$order.account_information.first_name} {$order.account_information.last_name}
{else}
{$order.account_information.first_name} {$order.account_information.last_name}
{/if}


{if $order.account_information.invoice_street|ne('')}{$order.account_information.invoice_street}{/if}

{$order.account_information.invoice_zip} {$order.account_information.invoice_city}

{if $order.account_information.invoice_state|ne('')}{$order.account_information.invoice_state}{/if}

{if $order.account_information.invoice_country|ne('')}{$order.account_information.invoice_country}{/if}
{/if}



{if and($order.account_information.comment, $order.account_information.comment|ne(''))}
3. {"Comment"|i18n('checkout/accountview/text')|upcase}
=======================================================

{$order.account_information.comment}
{/if}



