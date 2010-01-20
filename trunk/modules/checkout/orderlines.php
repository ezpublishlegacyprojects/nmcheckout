<?php 
include_once( "kernel/common/template.php" );
include_once( 'extension/nmcheckout/classes/checkout.php' );

$checkout 	= new checkout;
$tpl 		= templateInit();
$http 		= eZHTTPTool::instance();
$orderID	= $Params[ 'order_id' ];
$module		= $Params['Module'];

if ( 	$http->hasPostVariable( 'AddProduct' ) OR
		$http->hasPostVariable( 'StoreProductLinesButton' ) OR 
		$http->hasPostVariable( 'DeleteButton' ) 
)
{
	$save = $checkout->runProductLineAction( $_POST, $orderID );
	$tpl->setVariable( 'storedata', $save );
	if ( !$save[ 'is_ok' ] )
	{
		$tpl->setVariable( 'storedata', $save );
	}
}
if ( $http->hasPostVariable( 'CancelButton' ) )
{
	$module->redirectTo('shop/orderlist' );
}

$data = $checkout->getOrderDataByID( $orderID );

$tpl->setVariable('data', $data[ 'account_information' ] );
$tpl->setVariable( 'product_list', $data[ 'product_list' ] );
$tpl->setVariable( 'order_id', $orderID );
$tpl->setVariable( 'client_type', $checkout->getUserClientType( $data[ 'order' ]->attribute( 'user_id' ) ) );
$tpl->setVariable( 'order', $data[ 'order' ] );
						
$Result['content'] = $tpl->fetch( "design:checkout/orderlineform.tpl" );
?>