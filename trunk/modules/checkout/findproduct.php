<?php
$checkout = new checkout;
// get query
$q = $_GET['q'];
if ( strlen( $q ) > 2 )
{
	$result = $checkout->findProducts( $q );
	if ( count( $result ) > 0 )
	{
		foreach( $result as $product )
		{
			$identifier = $product->attribute( 'class_identifier' );
			$dataMap	= $product->attribute( 'data_map' );
			$name		= $checkout->ini->variable( 'ProductClassSettings_' . $identifier, 'Preview' );
			foreach( $checkout->ini->variable( 'ProductClassSettings_' . $identifier, 'FieldToSearchIn' ) as $field )
			{
				$name = str_replace( $field, $dataMap[$field]->content(), $name );
			}
			echo $name .'|'.$product->attribute( 'node_id' )."\n";
		}
	}
	else
	{
		echo ezi18n( 'checkout/register', 'No result found' ).'|0';
	}
}
else
{
	echo ezi18n( 'checkout/register', 'To short search string' ).'|0';
}
$Result[ 'pagelayout' ] =false;
?>