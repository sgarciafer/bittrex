<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require __DIR__.'/src/edsonmedina/bittrex/Client.php';
	require __DIR__.'/controller/controller.php';
	require __DIR__.'/config.php';

	use edsonmedina\bittrex\Client;

	$bittrex = new Client ($key, $secret);
	$controller = new controller($bittrex);

	//var_dump($b->getOrderBook('USDT-ETH','both','5'));

	$tickers = $bittrex->getTickerByDate ("USDT-ETH", "fiveMin", time());
	var_dump($tickers);

	/*
	$hist = $bittrex->getmarkethistory('USDT-ETH',50);
	$histCooked = $controller->parseHistory($hist,2);
	$controller->outputHistory($hist,2);
	echo('<pre>'.print_r($histCooked,-1).'</pre>');
	*/


	/*
	//var_dump ($b->getOrderHistory());
	$OrderHistory = $b->getOrderHistory();
	if(isset($OrderHistory[0])){
		//insertTx($type, $pair = "USDT-ETH", $amount, $price, $balance, $status = "OPEN")
		$c->insertTx($OrderHistory[0]->OrderType, $OrderHistory[0]->Exchange, $OrderHistory[0]->Quantity, $OrderHistory[0]->Limit, $OrderHistory[0]->Price, "OPEN");
	}
	//var_dump ($b->getBalance ("USDT"));

*/

	echo "\n\n";
