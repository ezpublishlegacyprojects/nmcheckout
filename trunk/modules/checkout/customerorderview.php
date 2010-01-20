<?php

$CustomerID = $Params['CustomerID'];
$Email 		= $Params['Email'];
$module 	= $Params['Module'];

require_once( "kernel/common/template.php" );
include_once( "extension/nmcheckout/classes/customezorder.php" );

$http = eZHTTPTool::instance();

$tpl = templateInit();

$Email = urldecode( $Email );

// the default eZOrder functions does not support fetching archived orders
$productList = customeZOrder::productList( $CustomerID, $Email );
$orderList = customeZOrder::orderList( $CustomerID, $Email );



$tpl->setVariable( "product_list", $productList );

$tpl->setVariable( "order_list", $orderList );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/customerorderview.tpl" );
$path = array();
$path[] = array( 'url' => '/shop/orderlist',
                 'text' => ezi18n( 'kernel/shop', 'Order list' ) );
$path[] = array( 'url' => false,
                 'text' => ezi18n( 'kernel/shop', 'Customer order view' ) );
$Result['path'] = $path;

?>
