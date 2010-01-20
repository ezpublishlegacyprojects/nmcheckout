<?php

include_once( "extension/nmvalidation/classes/validation.php" );
include_once( "extension/nmxml/classes/xml.php" );
include_once( "lib/ezutils/classes/ezoperationhandler.php" );
include_once( "kernel/classes/ezbasket.php" );	
class checkout
{
	var $ini;
	var $validation;
	var $user;
	var $shopAccountIdentifier;
	var $formFields;
	
	function checkout()
	{
		$this->shopAccountIdentifier = 'nmcheckout';
		
		$this->ini 		= eZINI::instance( 'nmcheckout.ini' );
		$this->siteIni	= eZINI::instance( 'site.ini' );
		
		// get current user
		$this->user = eZUser::currentUser();
		$this->formFields = $this->ini->variable( 'General', 'DefaultFormFields' );

	}
	
	function getFormFields()
	{
		return $this->formFields;
	}
	
	function getCurrentUserClientType()
	{
		$userObject = eZContentObject::fetch($this->user->ContentObjectID);
		
		return $userObject->mainParentNodeID();
	}
	function getUserClientType( $objectID )
	{
		$userObject = eZContentObject::fetch( $objectID );
		
		return $userObject->mainParentNodeID();
	}
	
	function validOrderRegistration($data)
	{
		// fetch client type from post data
		$clientType = $data['client_type'];
		
		// fetch validation rules for this client type
		$validationRulesArray = $this->ini->variableArray('ClientGroup_' . $clientType, 'ValidationRules');
		

		$validationSet = $this->formatValidationRules( $validationRulesArray );
	
		// if the user is not logged in
		if(!$this->user->isLoggedIn())
		{
			// add validation for email and password
			$validationSet['email'][] 				= 'email_not_taken_by_ez_user';
			$validationSet['password'][] 			= 'not_empty';
			$validationSet['password_confirm'][] 	= array('must_equal', 'password', ezi18n( 'checkout/register', 'Password' ));
		}
		
		// perform general validation
		$this->validation = new validation($data, $validationSet);
		
		return $this->validation->valid;
	}
	function formatValidationRules( $validationRulesArray )
	{
			// prepare validation set
		$validationSet = array();
		
		// if any validation rules exist
		if(is_array($validationRulesArray))
		{
			// for each validation rule
			foreach($validationRulesArray as $validationRule)
			{
				$fieldName 				= $validationRule[0];
				$validationRulesList 	= explode("|", $validationRule[1]);
				
				$validationSet[$fieldName] = $validationRulesList;
			}
		}
		return $validationSet;
	}
	function getOrderDataByID( $orderID )
	{
		$order = eZOrder::fetch( $orderID );

		if ( $order )
		{
			include_once( 'extension/nmcheckout/shopaccounthandlers/nmcheckoutshopaccounthandler.php' );
			$handler = new nmCheckoutShopAccountHandler();
			$accountInformation = $handler->accountInformation( $order );
			return array( 	'account_information' 	=> $accountInformation,
							'order'					=> $order,
							'product_list'			=> $order->productItems() ); 
		}
		return false;
		
	}
	function fetchOrderListByUser( $userID )
	{
		return eZPersistentObject::fetchObjectList( eZOrder::definition(),
                                                    null,
                                                    array( "user_id" => $userID),
                                                    array( "created" => "desc" ), null,
                                                   	true );
	}
	function getUserMostRecentOrderData()
	{
		// if the user is logged in
		if($this->user->isLoggedIn())
		{
			
			$clientType  = $this->getCurrentUserClientType();
			$excludeData = $this->ini->variable('ClientGroup_' . $clientType, 'ExcludeDataForCopyOrder');
			$returnData	 = array();
			// Check if user has an earlier order, copy order info from that one
			$orderList = $this->fetchOrderListByUser( $this->user->attribute( 'contentobject_id' ) );

			if ( count( $orderList ) > 0)
			{
			    $accountInfo = $orderList[0]->accountInformation();
				foreach( $accountInfo as $ident => $value )
				{
					if ( !in_array( $ident, $excludeData ) )
					{
						$returnData[ $ident ] = $value;
					}
				}
			    return $returnData;
			}
		}
	}
	
	function createWebShopUser($data)
	{
		// default user data
		$userClassID 		= $this->ini->variable( "WebShopUser", "UserClassID" );
    	$class 				= eZContentClass::fetch( $userClassID );
		$userCreatorID 		= $this->ini->variable( "WebShopUser", "UserCreatorID" );
    	$defaultSectionID 	= $this->ini->variable( "WebShopUser", "DefaultSectionID" );
    	$sendConfirmation	= $this->ini->variable( "WebShopUser", "SendConfirmationMail" );
		
    	// create content object
		$contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );
		
		// assign main node
		$nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $contentObject->attribute( 'id' ),
														   'contentobject_version' => 1,
														   'parent_node' => $data['client_type'],
														   'is_main' => 1 ) );
    	$nodeAssignment->store();
		
		// get data map
		$dataMap = $contentObject->attribute('data_map');
		
		// first name
		$dataMap['first_name']->setAttribute( 'data_text', $data['first_name'] );
		$dataMap['first_name']->store();
		
		// last name
		$dataMap['last_name']->setAttribute( 'data_text', $data['last_name'] );
		$dataMap['last_name']->store();
		
		// store user data
		$user = eZUser::fetch( $contentObject->attribute( 'id' ));
		$contentObjectID 	= $contentObject->attribute( 'id' );
		$login 				= $data['email'];
		$email				= $data['email'];
		$password			= $data['password'];
		$passwordConfirm	= $data['password_confirm'];
		$user->setInformation( $contentObjectID, $login, $email, $password, $passwordConfirm );
		$user->store();
		
		// store content object
		$contentObject->store();
		
		// publish object
		$operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' 	=> $contentObject->attribute( 'id' ),
                                                                                     'version' 		=> 1 ) );
		if ( $sendConfirmation == 1 )
		{
			$this->sendConfirmationEmailToNewWebShopUser( $data );
		}
		return $contentObject->attribute('id');
	}
	
	function sendConfirmationEmailToNewWebShopUser( $data )
	{
		include_once( "extension/nmobc/classes/phpmailer/class.phpmailer.php");
		$tpl = templateInit();
		$tpl->setVariable( 'data', $data );
		
		$body = $tpl->fetch( "design:checkout/email/confirmation_webshop_user.tpl" );
		
		$mail 				= new PHPMailer();
    	$mail->From     	= $this->siteIni->variable( 'MailSettings', 'EmailSender' );
		$mail->FromName 	= $this->siteIni->variable( 'MailSettings', 'AdminEmail' );
		$mail->AddAddress( $data[ 'email' ], $data[ 'first_name' ] . ' ' . $data[ 'last_name' ] );
		
		$mail->Subject  = ezi18n( 'nmcheckout/confirmationmail', 'Confirmation on registred user at %sitename (%siteurl)', null, array( '%sitename' => $this->siteIni->variable( 'SiteSettings', 'SiteName' ), '%siteurl' => $this->siteIni->variable( 'SiteSettings', 'SiteURL' ) ) );
		$mail->Body     = utf8_decode($body);
		
		$mail->Send();
		
	}
	function registerOrderData($data)
	{
		// if the user is not logged in
		if(!$this->user->isLoggedIn())
		{
			// create new user
			$userContentObjectID = $this->createWebShopUser($data);
			
			// log in new user
			$this->logInUser($userContentObjectID);
		}
		
		// fetch current basket
        $basket = eZBasket::currentBasket();

        // create order from basket
        $db =& eZDB::instance();
        $db->begin();
        $order = $basket->createOrder();
	
        $xmlParser = new xml();
        $xmlString = $xmlParser->create( $this->getFormFields(), $data, 'shop_account' );

        $order->setAttribute( 'data_text_1', $xmlString );
        $order->setAttribute( 'account_identifier', $this->shopAccountIdentifier );
        $order->setAttribute( 'ignore_vat', 0 );

        $order->store();
        $db->commit();

        include_once( 'kernel/shop/classes/ezshopfunctions.php' );
        eZShopFunctions::setPreferredUserCountry( $data['delivery_country'] );
        
        eZHTTPTool::setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );
	}
	
	
	function storeOrder( $data, $orderID )
	{
		if ( is_numeric( $orderID ) and is_array( $data ) )
		{
			$order 	= eZOrder::fetch( $orderID );
			$db 	=& eZDB::instance();
       		$db->begin();

       		$xmlParser = new xml();
        	$xmlString = $xmlParser->create( $this->getFormFields(), $data, 'shop_account' );
	        
	        $order->setAttribute( 'data_text_1', $xmlString );
	        $order->setAttribute( 'account_identifier', $this->shopAccountIdentifier );
	        $order->setAttribute( 'ignore_vat', 0 );
	
	        $order->store();
	        $db->commit();
	        
	        return true;
		}
		return false;
	}
	function deleteProductLines( $data )
	{
		if ( array_key_exists( 'deleteproduct', $data ) )
		{
			if ( is_array( $data[ 'deleteproduct' ] ) )
			{
				foreach( $data[ 'deleteproduct' ] as $productItemID )
				{
					$ezItem = eZProductCollectionItem::fetch( $productItemID );
					$db = eZDB::instance();
       				$db->begin();
					$ezItem->removeThis();
					$db->commit();
				}
			}
		}
	}
	function runProductLineAction( $data, $orderID )
	{
		$status = $this->storeProductLines( $data );
		$this->deleteProductLines( $data );
		$this->addProduct( $data, $orderID );
		return $status;
	}
	function findProductPriceIdentifier( $product )
	{	
		if ( is_object ( $product ) )
		{
			$productClassList 		= eZShopFunctions::productClassList();
			$productClassIdentifier = $product->ClassIdentifier;
			
			// find selected product class
			if ( count( $productClassList ) > 0 )
			{
			    if ( $productClassIdentifier )
			    {
			        foreach( $productClassList as $productClassItem )
			        {
			            if ( $productClassItem->attribute( 'identifier' ) === $productClassIdentifier )
			            {
			                $productClass = $productClassItem;
			                break;
			            }
			        }
			    }
			}
			
			if ( is_object( $productClass ) )
			{
			    $priceAttributeIdentifier = eZShopFunctions::priceAttributeIdentifier( $productClass );
			    return $priceAttributeIdentifier;
			}
		}
		return false;
	}
	function addProduct( $data, $orderID )
	{
	
		if ( is_numeric( $data[ 'product_search_id' ] ) )
		{
			$product 			= eZContentObjectTreeNode::fetch( $data[ 'product_search_id' ] );
			$priceIdentifier	= $this->findProductPriceIdentifier( $product );
			if ( $priceIdentifier )
			{
				$order 		= eZOrder::fetch( $orderID );
				$dataMap	= $product->attribute( 'data_map' );
				$price		= $dataMap[ $priceIdentifier ]->content();
				$isVatIncluded = 0;
				if ( $price->IsVATIncluded )
				{
					$isVatIncluded = 1;
				}
				$amount = 1;
				if ( $data[ 'product_amount' ] > 1 )
				{
					$amount = $data[ 'product_amount' ];
				}
				$newData = array( 
								'contentobject_id' 		=> $product->ContentObjectID, // the contentobject id for the product
								'discount'				=> 0,
								'is_vat_inc'			=> $isVatIncluded,
								'item_count'			=> $amount,
								'name'					=> $product->attribute( 'name' ), // object
								'price'					=> $price->Price, // price with . as decimal separator
								'productcollection_id'	=> $order->ProductCollectionID,
								'vat_value'				=> $price->VATType->Percentage// ini
								);
				$newProduct = new eZProductCollectionItem( $newData );
				$newProduct->store();
			}
		}
	}
	function storeProductLines( $data )
	{
		$resultList = array(	'is_ok' => true, 
								'error_list'	=> array() );
		if ( array_key_exists( 'product', $data ) )
		{
			if ( is_array( $data[ 'product' ] ) )
			{
				foreach( $data[ 'product' ] as $productLineID => $productData )
				{
					if ( $productData[ 'count_before_save' ] != $productData[ 'count' ] )
					{
						if ( $productData[ 'count' ] > 0  and is_numeric( $productData[ 'count'  ] ) )
						{
							$ezItem = eZProductCollectionItem::fetch( $productLineID );
							$ezItem->setAttribute( 'item_count', $productData[ 'count' ] );
							$ezItem->store();
						}
						else
						{	
							$resultList[ 'is_ok' ] = false;
							$resultList[ 'error_list' ][] = $productLineID;
						}
					}
				}
			}
		}
		return $resultList;
	}
	function logInUser($userContentObjectID)
	{
		// get current basket
		$basket = eZBasket::currentBasket();
		
		// fetch new user
		$user = eZUser::fetch($userContentObjectID);
		
		// log in user
        $user->loginCurrent();
        
        // update current user
        $this->user = eZUser::currentUser();
        
        // transfer the curent basket to the new user
        $http 	= eZHTTPTool::instance();
        $basket->setAttribute('session_id', $http->sessionID());
        $basket->store();
	}
	function findProducts( $query )
	{
		
		$productArray				= array();
		$productClassList 			= $this->ini->variable( 'AdminSettings', 'ProductClasses' );
		
		foreach( $productClassList as $productClassIdentifier )
		{
			$params		= array();
			$params[ 'ClassFilterType' ] 	= 'include';
			$params[ 'ClassFilterArray'] 	= array( $productClassIdentifier );
			$params[ 'Depth' ]				= $this->ini->variable( 'ProductClassSettings_' . $productClassIdentifier, 'Depth' );
			$params[ 'AttributeFilter' ]	= array( 'or' );
			foreach( $this->ini->variable( 'ProductClassSettings_' . $productClassIdentifier, 'FieldToSearchIn' ) as $field )
			{
				$params[ 'AttributeFilter' ][] = array( $productClassIdentifier .'/' .$field, 'like', "*". $query ."*" );
			}
			$result	 	= eZContentObjectTreeNode::subTreeByNodeID( $params, $this->ini->variable( 'ProductClassSettings_' . $productClassIdentifier, 'ParentNodeID' ) );
			$productArray = array_merge( $productArray, $result );
		}
		return $productArray;
	}
}

?>