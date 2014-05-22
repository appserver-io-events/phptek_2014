<?php

class Request extends Stackable {
    protected $message;
    public function __construct($message) {
        $this->message = $message;
    }
    public function run() {
        printf($this->message . PHP_EOL);
    }
}

class RequestHandler extends Worker {
    public function run()
    {}
}


$worker = new RequestHandler();
$work = array();

$i = 0;
while ($i ++ < 20) {
	$work[$i] = new Request("Request $i");
    $stacked = $worker->stack($work[$i]);
}

$worker->start();
$worker->shutdown();