<?php

include_once("extension/nmcheckout/classes/checkout.php");
include_once( "extension/nmxml/classes/xml.php" );

class nmCheckoutShopAccountHandler
{
    /*!
    */
    function nmCheckoutShopAccountHandler()
    {

    }

    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        return false;
    }

    /*!
     Redirectes to the user registration page.
    */
    function fetchAccountInformation( &$module )
    {
        $module->redirectTo( '/checkout/login/' );
    }

    /*!
     \return the account information for the given order
    */
    function email( $order )
    {
    	
        $email = false;
        $xmlString = $order->attribute( 'data_text_1' );
        
        $xmlParser 	= new xml();
        $data		= $xmlParser->parse( $xmlString );
        $data		= $data[ 'shop_account' ];
        $email		= $data[ 'email' ];

        return $email;
    	
    }

    /*!
     \return the account information for the given order
    */
    function accountName( $order )
    {
    	
        $accountName = '';
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
        	$xmlParser 	= new xml();
        	$data		= $xmlParser->parse( $xmlString );
        	$data		= $data[ 'shop_account' ];
        	$company	= $data[ 'company' ];
        	
        	if ( $company != '' and !is_array( $company ) )
        	{
        		$accountName = $company;
        	}
        	else
        	{
        		$accountName = $data[ 'first_name' ] . ' ' . $data[ 'last_name' ];
        	}
   

        }
        return $accountName;
    }

    function accountInformation( $order )
    {
    	
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
        	$xmlParser 			= new xml();
        	$checkout			= new checkout;
        	$data				= $xmlParser->parse( $xmlString );
        	$data				= $data[ 'shop_account' ];
        	$accountInformation = array();
        	$this->ini 			= eZINI::instance( 'nmcheckout.ini' );
        	
        	foreach( $checkout->getFormFields() as $fieldName )
        	{
        		if ( $data[ $fieldName ] != '' and !is_array( $data[ $fieldName] ) )
        		{
        			if ( $this->ini->variable( 'General', 'ActivateUTF8Decode' ) == 1 )
        			{
        				$accountInformation[ $fieldName ] = utf8_decode($data[ $fieldName ]);
        			}
        			else
        			{
        				$accountInformation[ $fieldName ] = $data[ $fieldName ];		
        			}
        		}
        		else
        		{
        			$accountInformation[ $fieldName ] = '';
        		}
        	}
        }
            
        return $accountInformation;
    }
    
	function fetchMamutOrderHead(&$order)
	{
	
		$accountInfo = $this->accountInformation($order);
		
		// get mamut contact ID
		include_once( "extension/mamut/classes/mamut.php" );
		$mamut = new mamut;
		$mamutContactID = $mamut->fetchMamutContactID($order);
	
		// order date time
	    $date                   = date("Y-m-d", $order->Created);
	    $time                   = date("H:i:s", $order->Created);
	    $dateAndTime    = $date . 'T' . $time;
	   
		$orderInfo 			= $order->orderInfo();
		$additional_info 	= $orderInfo[ 'additional_info' ];
		$ezcustomshipping 	= $additional_info[ 'ezcustomshipping' ];
		// NOTE: total_price_ex_vat is actually the price with vat. Whats wrong here I don`t know.
		$frakt				= $ezcustomshipping[ 'items' ][0]['total_price_ex_vat'];
		
		$result = array('orderId'				=> $order->ID,
						'orderDateTime'			=> $dateAndTime,
						'customerId'			=> $accountInfo["email"],
						'mamutContactId'		=> $mamutContactID,
						'customerCompanyName' 	=> $accountInfo["company"],
						'customerName'			=> $accountInfo["first_name"].' '.$accountInfo["last_name"],
						'customerFirstname'		=> $accountInfo["first_name" ],
						'customerLastname'		=> $accountInfo["last_name" ],
						'customerAddress1'		=> $accountInfo["delivery_street"],
						'customerAddress2'		=> $accountInfo["street2"], 
						'customerZipCode'		=> $accountInfo["delivery_zip"],
						'customerZipAddress'	=> $accountInfo["delivery_city"],
						'customerCountry'		=> $accountInfo["delivery_country"],
						'customerEmail'			=> $accountInfo["email"],
						'customerPhone'			=> $accountInfo["phone"],
						'customerMobile'		=> $accountInfo["phone"],
						'customerSendInfo'		=> '',
						'invoiceStreetAddress'	=> $accountInfo["invoice_street"],
						'invoiceZipCode'		=> $accountInfo["invoice_zip"],
						'invoiceZipAddress'		=> $accountInfo["invoice_city"],
						'invoiceCountry'		=> $accountInfo["invoice_country"],
						'deliveryName'			=> $accountInfo["first_name"].' '.$accountInfo["last_name"],
						'deliveryStreetAddress'	=> $accountInfo["delivery_street"],
						'deliveryZipCode'		=> $accountInfo["delivery_zip"],
						'deliveryZipAddress'	=> $accountInfo["delivery_city"],
						'deliveryCountry'		=> $accountInfo["delivery_country"], 
						'orderReference'		=> '',
						'orderAdditionalInfo'	=> '',
						'orderPaymentTerms'		=> $accountInfo[ "payment" ],
						'orderPaymentCost'		=> '',
						'orderDeliveryMethod'	=> $accountInfo[ "delivery" ],
						'orderDeliveryTerms'	=> '',
						'orderDeliveryCost'		=> '' );
	
		
		return $result;
	}

	function fetchMamutOrderLine(&$order)
	{
		// initate variables
		$orderLines = array();
		
		// for each order line
		foreach($order->productItems() as $key => $line)
		{
				$contentobject	 	= $line['item_object']->ContentObject;
				if ( is_object( $contentobject ) )
				{
					$datamap = $contentobject->dataMap();
					$mamut_product_id = $datamap[ 'mamut_product_id' ];
					if ( $mamut_product_id )
					{
						$product_number = $mamut_product_id->content();
					}
				}
				
			$orderLines[] = array(	'orderLineId' 	=> '', 
									'quantity' 		=> $line['item_count'], 
									'retailPrice' 	=> $line['price_ex_vat'], 
									'productId' 	=> '', 
									'productNumber' => $product_number, 
									'productName' 	=> $line['object_name'], 
									'vendorId' 		=> '', 
									'vendorName' 	=> '', 
									'taxPercent' 	=> '');
		}
		
		return $orderLines;
	}	
}

?>
