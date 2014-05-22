<?php

class Container extends Stackable {
    protected $counter = 0;
    public function run() {}
    public function count() {
        return $this->counter++;
    }
    public function getCounter() {
        return $this->counter;
    }
}

class Test extends Thread {
    protected $container;
    protected $mutex;
    public function __construct($container, $mutex) {
        $this->container = $container;
        $this->mutex = $mutex;
    }
    public function run() {
        for ($i = 0; $i < 1000; $i++) {
            Mutex::locK($this->mutex);
            $this->container->count();
            Mutex::unlocK($this->mutex);
        }
    }
}

$threads = array();
$counter = 0;
$container = new Container();
$mutex = Mutex::create(false);

while (++$counter < 5) {
    $threads[$counter] = new Test($container, $mutex);
    $threads[$counter]->start();
}

foreach ($threads as $thread) {
    $thread->join();
}

Mutex::unlock($mutex);
Mutex::destroy($mutex);

echo 'Counter is: ' . $container->getCounter() . PHP_EOL;