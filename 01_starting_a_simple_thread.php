<?php

class SomeThread extends Thread {
    protected $counter;
    public function run() {
        for ($i = 0; $i < 100000; $i++) {
        	$this->counter++;
        }
        echo 'Counter is: ' . $this->counter . PHP_EOL;
    }
}

$someThread = new SomeThread();
$someThread->start();

echo 'Finish script' . PHP_EOL;