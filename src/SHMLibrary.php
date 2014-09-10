<?php
namespace HMinng\SHMLibrary\Common;

use HMinng\SHMLibrary\Queue\Queue;
use HMinng\SHMLibrary\Memory\Memory;

class SHMLibrary
{
    public static function addMessageToQueue($string)
    {
        Queue::open();
        $status = Queue::stat();

        Memory::open();

        $memSize = Memory::read(100);
        if ($memSize === false) {
            $memSize = 0;
        }

        $size = Memory::getMemorySize($string) + $memSize;

        if ($size > $status['msg_qbytes']) {
            return false;
        }

        Memory::update(100, $size);

        Queue::add($string);

        return true;
    }

    public static function getQueueMessageNumbers()
    {
        Queue::open();

        $status = Queue::stat();
        return $status['msg_qnum'];
    }

    public static function getConfigures()
    {
        Memory::open();

        return Memory::read(1);
    }

    public static function addConfiguresToMemory($configureFile)
    {
        Memory::open();

        return Memory::add(1, $configureFile);
    }
}