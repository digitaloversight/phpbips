<?php

namespace DigitalOversight\PHPBips;

use DigitalOversight\PHPBips\Tests;
use DigitalOversight\PHPBips\Util\Base58;

use PHPUnit\Framework\TestCase;

class Base58Test extends TestCase
{
    public function testBase58Encode()
    {
        $base = new Base58();
        $this->assertEquals('3yZe7d', $base->encode('test'));
    }
}
