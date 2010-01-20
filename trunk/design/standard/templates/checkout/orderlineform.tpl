<form action="" method="post" id="product_list">
	<div class="context-block">
		<div class="box-header">
			<div class="box-tc">
				<div class="box-ml">
					<div class="box-mr">
						<div class="box-tl">
							<div class="box-tr">
								<h1 class="context-title">{'Edit products for order [%order_id]'|i18n( 'checkout/edit',, hash( '%order_id', $order.order_nr ) )}</h1>
								{* DESIGN: Mainline *}<div class="header-mainline"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-ml">
			<div class="box-mr">
				<div class="box-content">
					<div class="context-attributes">
						<div id="nmcheckout-order-view">
							{def $product_node=false()}
							{def $productid=0}
								{'Search for product to add to the order'|i18n('checkout/edit')}:<br/>
								<input type="text" id="product_amount" name="product_amount" class="amount" value="1" /> <input type="text" id="product_search" name="product_search" style="width: 200px;" value="" />
								<input type="hidden" id="product_search_id" name="product_search_id" value="" />
								<input class="button" type="submit" name="StoreProductLinesButton" value="{"Add product"|i18n( 'checkout/edit')}" />	
								<table cellspacing="0" class="list nmcheckout">
								<tr>
									<th class="tight">{'Amount'|i18n('checkout/edit')}</th>
									<th>{'Product'|i18n('checkout/edit')}</th>
									<th class="tight">{'Total price ex. vat'|i18n('checkout/edit')}</th>
									<th class="tight">{'Total price inc. vat'|i18n('checkout/edit')}</th>
									<th class="tight">&nbsp;</th>
								</tr>
								{foreach $product_list as $product sequence array( 'bglight', 'bgdark' ) as $style}
									{set $product_node=fetch( 'content', 'node', hash( 'node_id', $product.node_id ) )}
									{set $productid=$product.id}
									<tr class="{$style}">
										<td>
											<input type="text" class="amount{if $storedata.error_list|contains( $product.id )} error{/if}" name="product[{$product.id}][count]" value="{$product.item_count}" />
											<input type="hidden" name="product[{$product.id}][count_before_save]" value="{$product.item_count}" />
										</td>
										<td><a href={$product_node.url_alias|ezurl}>{$product.object_name}</a></td>
										<td class="nmcheckout_sum">{$product.total_price_ex_vat|l10n( 'currency' )}</td>
										<td class="nmcheckout_sum">{$product.total_price_inc_vat|l10n( 'currency' )}</td>
										<td><input type="checkbox" name="deleteproduct[]" value="{$product.id}" /></td>
									</tr>
									{if $storedata.error_list|contains( $product.id )}
										<tr class="{$style}">
											<td colspan="5" class="error">{'The product amount is not numeric and bigger than zero. Its now set back.'|i18n('checkout/edit')}</td>
										</tr>
									{/if}
								{/foreach}
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="controlbar">
				<div class="box-bc">
					<div class="box-ml">
						<div class="box-mr">
							<div class="box-tc">
								<div class="box-bl">
									<div class="box-br">
										<div class="block">
											<div class="button-right">
												<input class="button" type="submit" name="DeleteButton" value="{"Delete marked products"|i18n('checkout/edit')}" />
											</div>
											<div class="button-left">
												<input class="button" type="submit" name="StoreProductLinesButton" value="{"Update amount"|i18n( 'checkout/edit')}" />
											</div>
											<div class="break"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>