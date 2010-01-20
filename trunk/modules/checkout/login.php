<?php
include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezbasket.php" );

if ( phpversion() >= 5.0 )
{
	$module	= $Params['Module'];
	$http 	= eZHTTPTool::instance();
}
else
{
	$module =& $Params[ 'Module' ];
	$http 	=& eZHTTPTool::instance();
}
$redirectTo 				= $Params[ 'redirect' ];
$registerRedirectTo 		= $Params[ 'redirect' ];
$redirect_register_uri_set 	= true;
if (  $Params[ 'redirect' ] === false )
{
	$redirectTo = 'checkout/register';
	$registerRedirectTo = '';
	$redirect_register_uri_set = false;
}

// get current basket
$basket 	= eZBasket::currentBasket();
$loginerror = false;
if ( $http->hasPostVariable( 'LoginButton' ) )
{
	$login = eZUser::loginUser( $http->postVariable( 'Login' ), $http->postVariable( 'Password' ) );
	if ( !$login )
	{
		$loginerror = true;
	}
	if ( $http->hasPostVariable( 'RedirectURI' ) )
	{
		$redirectTo = $http->postVariable( 'RedirectURI' );
	}
}

$currentUser = eZUser::currentUser();

// if the current user is logged in, we do not need to log in again
if($currentUser->isLoggedIn())
{
	// transfer the curent basket to the new user
    $http 	= eZHTTPTool::instance();
    $basket->setAttribute('session_id', $http->sessionID());
    $basket->store();
	$module->redirectTo( $redirectTo );
}

// if the user is not logged in
else
{
	$tpl = templateInit();
	$tpl->setVariable( 'loginerror', $loginerror );
	$tpl->setVariable( 'redirect_uri', $redirectTo );
	$tpl->setVariable( 'redirect_register_uri', $registerRedirectTo );
	$tpl->setVariable( 'redirect_register_uri_set', $redirect_register_uri_set );
	
	$Result[ 'path' ] = array( 	array( 	'text'  => ezi18n( 'customshop', 'Shop' ),
										'url'  => '/'),
								array(	'text' => ezi18n( 'customshop', 'Login' ), 
										'url' => false  ) );
							
	$Result['content'] = $tpl->fetch( "design:checkout/login.tpl" );	
}

?>