<?php

$Module = array( "name" => "Checkout" );

$ViewList = array();

$ViewList["login"] 		= array("functions" 				=> array( 'login' ),
								"script" 					=> "login.php",
								"unordered_params"			=> array( 'redirect' => 'redirect' ));

$ViewList["register"] 	= array(	"script" 				=> "register.php",
								"functions" 				=> array('register'), 
    							"default_navigation_part" 	=> 'ezshopnavigationpart',
    							'single_post_actions' 		=> array( 'StoreButton' => 'Store',
                                    							'CancelButton' => 'Cancel'),
    							"params" 					=> array(  ) );
$ViewList["edit"] 		= array("functions" 				=> array( 'edit' ),
								"script" 					=> "edit.php",
								"params"					=> array( 'order_id' => 'order_id' ),
								'default_navigation_part' 	=> 'ezshopnavigationpart' );
$ViewList["orderlines"] = array("functions" 				=> array( 'orderlines' ),
								"script" 					=> "orderlines.php",
								"params"					=> array( 'order_id' => 'order_id' ) );
$ViewList["findproduct"] = array("functions" 				=> array( 'findproduct' ),
								"script" 					=> "findproduct.php" );
$ViewList["mypage"] 	= array("functions" 				=> array( 'mypage' ),
								"script" 					=> "mypage.php" );
$ViewList["customerorderview"] = array(
							    "functions" => array( 'administrate' ),
							    "script" => "customerorderview.php",
							    "default_navigation_part" => 'ezshopnavigationpart',
							    "params" => array( "CustomerID", "Email" ) );


$FunctionList['register'] 		= array( );
$FunctionList['login'] 			= array( );
$FunctionList['edit'] 			= array( );
$FunctionList['orderlines'] 	= array( );
$FunctionList['findproduct'] 	= array( );
$FunctionList['mypage'] 		= array( );
$FunctionList['administrate'] 	= array( );

?>
