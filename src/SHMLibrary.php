<?php
use HMinng\SHMLibrary\Queue\Queue;
use HMinng\SHMLibrary\Memory\Memory;

class SHMLibrary
{
    public static function addMessageToQueue($class)
    {
        Queue::open();
        $status = Queue::stat();
        print_r($status);
    }

    public static function add()
    {

    }
}