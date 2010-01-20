<?php 
include_once( "kernel/common/template.php" );
include_once( 'extension/nmcheckout/classes/checkout.php' );

$checkout 	= new checkout;
$tpl 		= templateInit();
$http 		= eZHTTPTool::instance();

$currentUser	= eZUser::currentUser();
$currentUserID	= $currentUser->ContentObjectID;
if ( $currentUserID != 10 )
{
	$tpl->setVariable( 'order_list',  eZOrder::activeByUserID( $currentUserID ) );
	$tpl->setVariable( 'loggedin', true );
}
else
{
	$tpl->setVariable( 'loggedin', false );
}
$Result['content'] 	= $tpl->fetch( "design:checkout/mypage.tpl" );

$Result['path'] 	= array( 
						array( 'text' => ezi18n( 'checkout/design', 'My page' ), 'url'	=> false )
							 );
?>