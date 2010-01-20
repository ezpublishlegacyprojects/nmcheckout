<?php

// the default ezorder class does not include archived orders when fetching orders in general

class customeZOrder extends eZOrder
{
	function customeZOrder()
	{
		parent::eZOrder(array());
	}
	
	static function orderList( $CustomID, $Email )
    {
        $db = eZDB::instance();
        $CustomID =(int) $CustomID;
        $Email = $db->escapeString( $Email );
        if ( $Email == false )
        {
            $orderArray = $db->arrayQuery( "SELECT ezorder.* FROM ezorder
                                            WHERE user_id='$CustomID'
                                              AND is_temporary='0'
                                         ORDER BY order_nr" );
        }
        else
        {
            $orderArray = $db->arrayQuery( "SELECT ezorder.* FROM ezorder
                                            WHERE user_id='$CustomID'
                                              AND is_temporary='0'
                                              AND email='$Email'
                                         ORDER BY order_nr" );
        }
        $retOrders = array();
        foreach( $orderArray as $orderRows )
        {
            $retOrders[] = new eZOrder( $orderRows );
        }
        return $retOrders;
    }
    
	static function productList( $CustomID, $Email )
    {
        $db = eZDB::instance();
        $CustomID =(int) $CustomID;
        $Email = $db->escapeString( $Email );
        if ( $Email == false )
        {
            $productArray = $db->arrayQuery(  "SELECT ezproductcollection_item.*, ignore_vat, currency_code FROM ezorder, ezproductcollection_item, ezproductcollection
                                                WHERE ezproductcollection.id=ezproductcollection_item.productcollection_id
                                                  AND ezproductcollection_item.productcollection_id=ezorder.productcollection_id
                                                  AND user_id='$CustomID'
                                                  AND is_temporary='0'
                                             ORDER BY contentobject_id, currency_code" );
        }
        else
        {
            $productArray = $db->arrayQuery(  "SELECT ezproductcollection_item.*, ignore_vat, currency_code FROM ezorder, ezproductcollection_item, ezproductcollection
                                                WHERE ezproductcollection.id=ezproductcollection_item.productcollection_id
                                                  AND ezproductcollection_item.productcollection_id=ezorder.productcollection_id
                                                  AND user_id='$CustomID'
                                                  AND is_temporary='0'
                                                  AND email='$Email'
                                             ORDER BY contentobject_id, currency_code" );
        }
        $currentContentObjectID = 0;
        $productItemArray = array();
        $productObject = null;
        $itemCount = 0;
        $name = false;
        $productInfo = array();

        foreach( $productArray as $productItem )
        {
            $itemCount++;
            $contentObjectID = $productItem['contentobject_id'];
            if ( $productObject == null )
            {
                if ( $contentObjectID != 0 )
                {
                    $productObject = eZContentObject::fetch( $contentObjectID );
                }
                else
                {
                    $productObject = null;
                    $name = $productItem['name'];
                }
                $currentContentObjectID = $contentObjectID;
            }
            if ( $currentContentObjectID != $contentObjectID && $itemCount != 1 )
            {
                $productItemArray[] = array( 'name' => $name,
                                             'product' => $productObject,
                                             'product_info' => $productInfo );
                unset( $productObject );
                $productInfo = array();
                $name = $productItem['name'];
                $currentContentObjectID = $contentObjectID;
                if ( $contentObjectID != 0 )
                {
                    $productObject = eZContentObject::fetch( $currentContentObjectID );
                }
                else
                {
                    $productObject = null;
                }
            }

            $currencyCode = $productItem['currency_code'];
            if ( $currencyCode == '' )
            {
                $currencyCode = eZOrder::fetchLocaleCurrencyCode();
            }

            if ( !isset( $productInfo[$currencyCode] ) )
            {
                $productInfo[$currencyCode] = array( 'sum_count' => 0,
                                                     'sum_ex_vat' => 0,
                                                     'sum_inc_vat' => 0 );
            }

            if ( $productItem['ignore_vat'] == true )
            {
                $vatValue = 0;
            }
            else
            {
                $vatValue = $productItem['vat_value'];
            }

            $count = $productItem['item_count'];
            $discountPercent = $productItem['discount'];

            $isVATIncluded = $productItem['is_vat_inc'];
            $price = $productItem['price'];

            if ( $isVATIncluded )
            {
                $priceExVAT = $price / ( 100 + $vatValue ) * 100;
                $priceIncVAT = $price;
                $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                $totalPriceExVAT = round( $totalPriceExVAT, 2 );
                $totalPriceIncVAT = round( $totalPriceIncVAT, 2 );
            }
            else
            {
                $priceExVAT = $price;
                $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                $totalPriceExVAT = $count * $priceExVAT  * ( 100 - $discountPercent ) / 100;
                $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100 ;
                $totalPriceExVAT = round( $totalPriceExVAT, 2 );
                $totalPriceIncVAT = round( $totalPriceIncVAT, 2 );
            }

            $productInfo[$currencyCode]['sum_count'] += $count;
            $productInfo[$currencyCode]['sum_ex_vat'] += $totalPriceExVAT;
            $productInfo[$currencyCode]['sum_inc_vat'] += $totalPriceIncVAT;
        }
        if ( count( $productArray ) != 0 )
        {
            $productItemArray[] = array( 'name' => $name,
                                         'product' => $productObject,
                                         'product_info' => $productInfo );
        }
        return $productItemArray;
    }
}

?>