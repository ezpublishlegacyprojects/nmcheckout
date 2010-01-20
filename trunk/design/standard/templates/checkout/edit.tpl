<form action="" method="post" id="register-order">
	<div class="context-block">
		<div class="box-header">
			<div class="box-tc">
				<div class="box-ml">
					<div class="box-mr">
						<div class="box-tl">
							<div class="box-tr">
								<h1 class="context-title">{'Edit order [%count]'|i18n( 'checkout/edit',, hash( '%count', $order.order_nr ) )}</h1>
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
							{if eq( $order.account_identifier, 'nmcheckout' )}
									{include uri="design:parts/register_form.tpl" data=$data}
							{else}
								<div class="mainobject-window">
									{'This order was made with another shouaccounthandler, and could not be edited.'|i18n('checkout/edit')}<br/><br/>
									{'Please contact the site administrator if you have questions about this.'|i18n('checkout/edit')}
								</div>
							{/if}
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
										<div class="button-left">
											{if eq( $order.account_identifier, 'nmcheckout' )}
												<input class="button" type="submit" name="StoreButton" value="{"Save"|i18n( 'checkout/register')}" />
											{/if}
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
</form>
{if ezini( 'AdminSettings', 'CanEditProductItems', 'nmcheckout.ini' ) )}
	{include uri="design:checkout/orderlineform.tpl" product_list=$product_list storedata=$storedata order=$order}
{/if}