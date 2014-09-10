<?php
namespace HMinng\SHMLibrary\Queue;

class Queue
{
    const DEFAULT_SHM_ID = 2014061914;

    const DEFAULT_PERMISSION = 0644;

    private static $shmId = NUll;

    public static function open()
    {
        if (is_null(self::$shmId)) {
            self::$shmId = msg_get_queue(self::DEFAULT_SHM_ID, self::DEFAULT_PERMISSION);

            if (self::$shmId === false) {
                throw new \Exception('Unable to create the message queue segment');
            }
        }

        return self::$shmId;
    }

    public static function add($value)
    {
        $flag = msg_send(self::$shmId, 2, $value, true, false, $error);

        if (!$flag) {
            throw new \Exception('Add queue is failure!');
        }

        return true;
    }

    public static function read()
    {
        $flag = msg_receive(self::$shmId, 0, $type, 4096, $message, true, MSG_IPC_NOWAIT);

        if (!$flag) {
            throw new \Exception('The queue is not found!');
        }

        return  $message;
    }

    public static function isExists()
    {
        return msg_queue_exists(self::DEFAULT_SHM_ID);
    }

    public static function stat()
    {
        return msg_stat_queue(self::$shmId);
    }

    public static function remove()
    {
        $flag = msg_remove_queue(self::$shmId);

        if ( ! $flag) {
            throw new \Exception('Remove segment identifier failure!');
        }

        return true;
    }
}