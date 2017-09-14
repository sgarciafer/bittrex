<?php


class controller{

  private $dbPass = "root";
  private $dbLogin = "root";
  private $dbName = "robotrex";
  private $dbHost = "localhost";
  private $dbConn;
  private $logDir = __DIR__ . "/../logs/";

  private $bittrex;

  public function __construct($bittrex) {
    $this->bittrex = $bittrex;
  }


  public function parseHistory($hist,$timeRange){
    $output = [];
    $output['hist'] = $this->bittrex->getmarkethistory('USDT-ETH',30);
    $output['bands'] = [];
    $output['bands']['average'] = [];

		$stats = array();
		$rangeStamp = strtotime($this->getCurrentTimeRange($timeRange));
    $averageCounter = 0;
    $statCounter = 0;

		foreach( $output['hist'] as $index => $filled) {
			if(isset($filled->OrderType)) {
        if( $filled->TimeStamp <= $rangeStamp - 60 * $timeRange ) {
          $rangeStamp -= 60 * $timeRange;
          $averageCounter = 0;
          $output['bands']['average'][$statCounter] = $output['bands']['average'][$statCounter]/ ($averageCounter+1);
          $statCounter++;
        }
        if(!isset($output['bands']['average'][$statCounter])) $output['bands']['average'][$index] = 0;
        $output['bands']['average'][$statCounter] += $filled->Price;
        $averageCounter++;
			}
		}
    return $output;
	}

  public function outputHistory($hist) {
    echo('<div style="width:400px;height:200px;overflow:scroll;border:1px solid #666;"><pre>'.print_r($hist,-1)."</pre></div>");
  	echo('<div style="width:800px;height:400px;overflow:scroll;border:1px solid #666;">');
  	foreach( $hist as $index => $filled) {
  		if(isset($filled->OrderType)) {
  			$time = $filled->TimeStamp;
  			$bgColor = "#d18787";
  			if($filled->OrderType == "BUY") $bgColor = "#99cc99";
  			echo('<div style="background:'.$bgColor.'"><span style="padding-left:10px;display: inline-block; width: 100px;color:#222;">'.$filled->Price.'</span>&nbsp;<span style="display: inline-block;color: #222;width: 100px;">'.$filled->Quantity.'ETH</span> <span style="color: #222;text-align: right;display: inline-block; width: 200px;">'.$filled->Total.' USDT</span><span style="display: inline-block;color: #222;width:300px;text-align:right">'.$time.'</span></div>' );
  		}
  	}
  	echo('</div>');
  }

  public function insertTx($type, $pair = "USDT-ETH", $amount, $price, $balance, $status = "OPEN"){
    $this->connectSQL();
    $sql = "INSERT INTO transactions (type, pair, amount, price, balance, status)
            VALUES ( '$type', '$pair', '$amount', '$price', '$balance', '$status')";
    if ($this->dbConn->query($sql) === TRUE) {
        $this->writeLog("New tx inserted ( ".$status." ): " . $type . " " . $pair . " ". $amount ." at " . $price . " producing " . $balance );
    } else {
        $this->writeLog("Error: " . $sql . "<br>" . $this->dbConn->error);
    }
    $this->dbConn->close();
  }

  private function connectSQL(){
    $this->dbConn = new mysqli($this->dbHost, $this->dbLogin, $this->dbPass, $this->dbName);
    if ($this->dbConn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
  }

  private function writeLog($message) {
    $data = "[".time()."] ".$message. "\r\n";
    $logname = date('Ymd', time()).'.txt';
    file_put_contents( $this->logDir.$logname , $data , FILE_APPEND | LOCK_EX );
  }

  /**
   * Helper function, return the current X minutes max time range.
   * @timeRange: the time in minutes that would be represented by a single candlestick on a graphic chart.
   **/
  private function getCurrentTimeRange($timeRange){
    $min = date('i') +($timeRange - (date('i') % $timeRange));
    return date('Y-m-d').'T'.date('H').':'.$min.':00';
  }
}
