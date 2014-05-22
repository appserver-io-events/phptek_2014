<?php

class Test extends Thread {
    protected $socket;
    public function __construct($socket) {
        $this->socket = $socket;
    }
    public function run() {
        $response = array(
            "head" => "HTTP/1.0 200 OK\r\nContent-Type: text/html\r\n",
            "body" => '<html><head><title>A Title</title></head><body><p>A content</p></body></html>'
        );
        while ($client = socket_accept($this->socket)) {
            $buffer = '';
            while ($buffer .= socket_read($client, 1024)) {
                if (false !== strpos($buffer, "\r\n\r\n")) {
                    break;
                }
            }
            socket_write($client, implode("\r\n", $response));
            socket_close($client);
        }
    }
}

$workers = array();
$socket = socket_create_listen($argv[1]);

if ($socket) {
    $worker = 0;
    while (++$worker < 5) {
        $workers[$worker] = new Test($socket);
        $workers[$worker]->start();
    }
    printf("%d threads waiting on port %d", count($workers), $argv[1]);
    foreach ($workers as $worker) {
        $worker->join();
    }
    printf("Successfully served %d requests\n", $container->getCounter());
}