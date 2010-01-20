{set-block scope=root variable=cache_ttl}0{/set-block}
<div class="border-box">
	<div class="border-tl">
		<div class="border-tr">
			<div class="border-tc">
			</div>
		</div>
	</div>
	<div class="border-ml">
		<div class="border-mr">
			<div class="border-mc float-break">
				<div class="shop-mypage">
					{if $loggedin}
						<h1>{"My page"|i18n('checkout/design')}</h1>
						<table>
							<tr class="table-head">
								<th class="order">{"Order"|i18n('checkout/mypage')}</th>
								<th class="cash">{"Total (ex. VAT)"|i18n('checkout/mypage')}</th>
								<th class="cash">{"Total (inc. VAT)"|i18n('checkout/mypage')}</th>
								<th class="date">{"Date"|i18n('checkout/mypage')}</th>
								<th class="status">{"Status"|i18n('checkout/mypage')}</th>
							</tr>
							{foreach $order_list as $order sequence array('bglight', 'bgdark') as $style}
							<tr class="{$style}">
								<td class="order"><a href={concat( 'shop/orderview/', $order.id)|ezurl} title="{"Click to see the details of your order"|i18n('checkout/mypage')}">{"Order no. %1"|i18n('checkout/mypage',,array( $order.order_nr ) ))}</a></td>
								<td class="cash">{$order.order_info.total_price_info.total_price_ex_vat|l10n('currency')}</td>
								<td class="cash">{$order.order_info.total_price_info.total_price_inc_vat|l10n('currency')}</td>
								<td class="date">{$order.created|datetime( 'custom', '%d.%m.%Y %H:%i')}</td>
								<td class="status">{$order.status.name}</td>
							</tr>
							{/for}
						</table>
					{else}
						<h1>{"Login needed"|i18n('checkout/design')}</h1>
						{'You need to be logged in to see this page.'|i18n('checkout/mypage')} <a href={'user/login'|ezurl}>{'You can log in here.'|i18n('checkout/mypage')}</a>
					{/if}
				</div>
			</div>
		</div>
	</div>
</div>