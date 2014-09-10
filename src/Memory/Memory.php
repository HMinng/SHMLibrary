<?php
namespace HMinng\SHMLibrary\Memory;

class Memory
{
    const DEFAULT_SHM_ID = 2014061914;

    const DEFAULT_PERMISSION = 0644;

    const DEFUALT_MEMORY_SIZE = 4000000;

    private static $shmId = NUll;

    public static function open()
    {
        if (is_null(self::$shmId)) {
            self::$shmId = shm_attach(self::DEFAULT_SHM_ID, self::DEFUALT_MEMORY_SIZE, self::DEFAULT_PERMISSION);

            if (self::$shmId === false) {
                throw new \Exception('Unable to create the shared memory segment');
            }
        }

        return self::$shmId;
    }

    public static function add($key, $value)
    {
        $flag = shm_has_var(self::$shmId, $key);

        if ($flag) {
            shm_remove_var(self::$shmId, $key);
        }

        $flag = shm_put_var(self::$shmId, $key, $value);

        if (!$flag) {
            throw new \Exception('Add key is failure!');
        }

        return true;
    }

    public static function update($key, $value)
    {
        $flag = shm_put_var(self::$shmId, $key, $value);

        if (!$flag) {
            throw new \Exception('Update key is failure!');
        }

        return true;

    }

    public static function read($key)
    {
        $flag = shm_has_var(self::$shmId, $key);

        if (!$flag) {
            return false;
        }

        return  shm_get_var(self::$shmId, $key);
    }

    public static function remove()
    {
        $flag = (self::$shmId);

        if ( ! $flag) {
            throw new \Exception('Remove segment identifier failure!');
        }

        return true;
    }

    public static function close()
    {
        return shm_detach(self::$shmId);
    }

    public static function getMemorySize($string)
    {
        $headSize = (PHP_INT_SIZE * 4) + 8;
        $shmVarSize = (((strlen(serialize($string)) + (4 * PHP_INT_SIZE)) /4 ) * 4 ) + 4;
        return  $headSize + $shmVarSize;
    }
}