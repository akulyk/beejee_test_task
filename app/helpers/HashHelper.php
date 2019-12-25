<?php
namespace app\helpers;

class HashHelper
{
    /**
     * @param int $length
     * @return string
     */
    public static function generate($length = 20): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return hash('sha256', $randomString);
    }

    /**
     * @param string $password
     * @return string
     */
    public static function crypt(string $password): string
    {
        return md5($password);
    }
}
