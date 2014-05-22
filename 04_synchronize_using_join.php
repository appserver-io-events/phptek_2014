<?php

class RenderHtml extends Thread
{

    public function run()
    {

		$images = array('image_01.gif', 'image_02.gif', 'image_03.gif');

		$imageThreads = array();
		for ($i = 0; $i < sizeof($images); $i++) {
    		$imageThreads[$i] = new RenderImage($images[$i]);
    		$imageThreads[$i]->start();
		}
        
        foreach ($imageThreads as $imageThread) {
        	$imageThread->join();
        }
        
        $imageUrls = array();
        
        foreach ($imageThreads as $imageThread) {
            $imageUrls[] = '<img src="' . $imageThread->getImageUrl() . '"/>';
        }
        
        echo '<html><head></head><body>' . implode('<br/>', $imageUrls) . '</body></html>';
    }
}

class RenderImage extends Thread {
    protected $image;
    public function __construct($image) {
        $this->image = $image;
    }
    public function run() {
        sleep(3);
        // render images here ....
        $this->imageUrl = 'http://localhost/images/' . $this->image;
    }
    public function getImageUrl() {
        return $this->imageUrl;
    }
}

$renderHtml = new RenderHtml();
$renderHtml->start();