{'Hi %firstname %lastname'|i18n('checkout/confirmationmail', false(), hash( '%firstname', $data.first_name, '%lastname', $data.last_name ) )}

{'We have registred the following on you'|i18n('checkout/confirmationmail')}

{'User account'|i18n('checkout/confirmationmail')|upcase}
=======================================================
{'Username'|i18n('checkout/confirmationmail')}: {$data.email}
{'Password'|i18n('checkout/confirmationmail')}: {$data.password}


{'Contact details'|i18n('checkout/confirmationmail')|upcase}
=======================================================
{"Client"|i18n('checkout/confirmationmail')}: {if $data.company|ne('')}{$data.company}{else}{$data.first_name} {$data.last_name}{/if}


{if $data.company|ne('')}{"VAT no."|i18n('checkout/confirmationmail')}: {$data.vat_no}
{"Contact"|i18n('checkout/confirmationmail')}: {$data.first_name} {$data.last_name}{/if}

{'Email'|i18n('checkout/confirmationmail')}: {$data.email}
{'Phone'|i18n('checkout/confirmationmail')}: {$data.phone}

{if $data.delivery|ne('')}{"Delivery"|i18n('checkout/confirmationmail')}: {def $default_delivery_methods=ezini('General', 'DefaultDeliveryMethods', 'nmcheckout.ini')}{foreach $default_delivery_methods as $delivery}{def $parts=$delivery|explode(';')}{if eq($parts.1, $data.delivery)}{$parts.0}{/if}{/foreach}{/if}



{if $data.payment|ne('')}{"Payment"|i18n('checkout/confirmationmail')}: {$data.payment}{/if}


{if ne( $data.invoice_as_delivery, 'yes' )}
{'Delivery address'|i18n('checkout/confirmationmail')|upcase}
{else}
{'Delivery and invoice adress'|i18n('elby/confirmationmail')|upcase}
{/if}

======================================================

{if $data.company|ne('')}
{$data.company}
{"Att"|i18n('checkout/confirmationmail')}: {$data.first_name} {$data.last_name}
{else}
{$data.first_name} {$data.last_name}
{/if}


{$data.delivery_street}
{$data.delivery_zip} {$data.delivery_city}

{if $data.delivery_state|ne('')}{$data.delivery_state}{/if}

{if $data.delivery_country|ne('')}{$data.delivery_country}{/if}


{if ne( $data.invoice_as_delivery, 'yes' )}
{'Invoice address'|i18n('elby/confirmationmail')|upcase}
======================================================

{if $data.company|ne('')}
{$data.company}
{"Att"|i18n('checkout/confirmationmail')}: {$data.first_name} {$data.last_name}
{else}
{$data.first_name} {$data.last_name}
{/if}


{$data.invoice_street}
{$data.invoice_zip} {$data.invoice_city}

{if $data.invoice_state|ne('')}{$data.invoice_state}{/if}

{if $data.invoice_country|ne('')}{$data.invoice_country}{/if}
{/if}
