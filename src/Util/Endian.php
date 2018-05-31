<?php

/**
 * This file is part of PHPBips.
 * Endian operations
 *
 * (c) Vincent A. Menard <root@aifs.io>
 */

namespace DigitalOversight\PHPBips\Util;

class Endian
{

    /**
     * Convert string of bytes Python integer, MSB
     */
    public function string_to_int(string $data): int
    {
        $val = 0;
        for ($i=0; $i<strlen($data); $i++) {
            $val += (256**$i)*uniord($data[$i]);
        }
        return val;
    }

    private function uniord($s):integer
    {
        return unpack('V', iconv('UTF-8', 'UCS-4LE', $s))[1];
    }
}
