<?php

include_once( "kernel/common/template.php" );
include_once( "extension/nmcheckout/classes/checkout.php" );

$tpl 		= templateInit();
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
$checkout 	= new checkout;

$customRedirect = false;

// check if RedirectURI i set, if they don`t wan`t to go to the checkout view after registered one user.
if ( $http->hasPostVariable( 'RedirectURI' ) )
{
	$redirectTo 	= $http->postVariable( 'RedirectURI' );
	$customRedirect = true;
	$tpl->setVariable( 'redirect_uri', $redirectTo );
	$tpl->setVariable( 'redirect_set', $customRedirect );
}

// if the user is logged in
if($checkout->user->isLoggedIn())
{
	// get user client type
	$clientType = $checkout->getCurrentUserClientType();
	
	// fetch most recent order data
	$data = $checkout->getUserMostRecentOrderData();
}
else
{
	// if a client type is specified
	if($http->hasPostVariable('client_type'))
	{
		$clientType = $http->postVariable('client_type');
	}
	else
	{
		echo "Uknown client type";
		$module->redirectTo( 'checkout/login' );
	}
}

// if the registration has been posted on cancel button
if ( $http->hasPostVariable( 'CancelButton' ) )
{
	// if redirect is set redirect to that page instead.
	if ( $customRedirect )
	{
		$module->redirectTo( $redirectTo );
	}
	else
	{
		$module->redirectTo( 'shop/basket' );
	}
}

// if the registration has been posted
if($http->hasPostVariable('StoreButton'))
{
	// if the order registration is invalid
	if(!$checkout->validOrderRegistration($_POST))
	{
		// set validation errors and data in template
		$tpl->setVariable('validation', $checkout->validation->msgs);
		$data = $_POST;
		
	}
	
	// if the order is valid
	else
	{
		// register order data
		$checkout->registerOrderData($_POST);
		
		// if custome redirect is set
		if ( $customRedirect )
		{
			$module->redirectTo( $redirectTo );
		}
		// redirect to confirm order step
		else
		{
        	$module->redirectTo( '/shop/confirmorder/' );
		}
        return;
	}
}

$validationRulesArray 	= $checkout->ini->variableArray('ClientGroup_' . $clientType, 'ValidationRules');
$validationRules		= $checkout->formatValidationRules( $validationRulesArray );
$tpl->setVariable('client_type', $clientType);
$tpl->setVariable('data', $data);
$tpl->setVariable( 'validation_rules', $validationRules );

$Result[ 'path' ] = array( 
						array( 'text'  => ezi18n( 'customshop', 'Shop' ),
								'url'  => '/'),
						array(	'text' => ezi18n( 'customshop', 'Register' ), 
								'url' => false  ) );
						
$Result['content'] = $tpl->fetch( "design:checkout/register.tpl" );

?>