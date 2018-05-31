<?php

/**
 * This file is part of PHPBips.
 * Base58 encoder and decoder
 *
 * (c) Vincent A. Menard <root@aifs.io>
 */

namespace DigitalOversight\PHPBips\Util;

class Base58
{
    const alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';
    private $method;

    /**
     *
     */

    public function __construct()
    {
        if (extension_loaded('gmp') || dl(gmp.so)) {
            $this->method = 1;
        }
    }

    /**
     * Encode string into Bitcoin base58
     *
     * @param  string $string The string to encode.
     * @return string The Base58 encoded string.
     */

    public static function encode($string) : string
    {
        $hex = unpack('H*', $string);
        $hex = reset($hex);
        $decimal = gmp_init($hex, 16);
        $output = '';

        while (gmp_cmp($decimal, strlen(self::alphabet)) >= 0) {
            list($decimal, $mod) = gmp_div_qr($decimal, strlen(self::alphabet));
            $output .= self::alphabet[gmp_intval($mod)];
        }

        if (gmp_cmp($decimal, 0) > 0) {
            $output .= self::alphabet[gmp_intval($decimal)];
        }

        $output = strrev($output);
        $bytes = str_split($string);
        foreach ($bytes as $byte) {
            if ($byte === "\x00") {
                $output = self::alphabet[0] . $output;
                continue;
            }
            break;
        }
        return (string) $output;
    }


    /**
     * Decode base58 into a string.
     *
     * @param  string $base58 The base58 encoded string.
     * @return string The decoded string.
     */

    public function decode(string $base58) : ?string
    {
        $indexes = array_flip(str_split(self::alphabet));
        $chars = str_split($base58);
        $decimal = gmp_init($indexes[$chars[0]], 10);
        for ($i = 1, $l = count($chars); $i < $l; $i++) {
            $decimal = gmp_mul($decimal, strlen(self::alphabet));
            $decimal = gmp_add($decimal, $indexes[$chars[$i]]);
        }
        $output = '';
        while (gmp_cmp($decimal, 0) > 0) {
            list($decimal, $byte) = gmp_div_qr($decimal, 256);
            $output = pack('C', gmp_intval($byte)) . $output;
        }
        foreach ($chars as $char) {
            if ($indexes[$char] === 0) {
                $output = "\x00" . $output;
                continue;
            }
            break;
        }
        return $output;
    }
}
