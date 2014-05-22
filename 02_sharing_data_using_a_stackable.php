<?php

class Container extends Stackable {
    public function run() {}
}

class Test extends Thread {

    protected $file;
    protected $container;

    public function __construct($file, $container) {
        $this->file = $file;
        $this->container = $container;
    }

    public function run() {
        $start = microtime(true);
        $key = key($this->file);
        file_put_contents('php://temp', file_get_contents($this->file[$key]));
        $this->container[$key] = microtime(true) - $start;
    }
}

$container = new Container();

$files = array(
    array('10kB' => 'http://www.speedtest.qsc.de/10kB.qsc'),
    array('100kB' => 'http://www.speedtest.qsc.de/100kB.qsc'),
    array('10MB' => 'http://www.speedtest.qsc.de/10MB.qsc')
);

$counter = 0;
foreach ($files as $file) {
    $threads[$counter] = new Test($file, $container);
    $threads[$counter]->start();
}

foreach ($threads as $thread) {
    $thread->join();
}

foreach ($files as $file) {
    $key = key($file);
    echo "Thread $file[$key] took: $container[$key] sec" . PHP_EOL;
}