<?php

namespace DigitalOversight\PHPBips;

use DigitalOversight\PHPBips\Tests;
use DigitalOversight\PHPBips\BipFactory;
use DigitalOversight\PHPBips\Bips\BipInterface;

use PHPUnit\Framework\TestCase;

class Bip32Tests extends TestCase
{
    public function testInstanceOf()
    {
        $bip = BipFactory::getBip(32);
        $this->assertInstanceOf('DigitalOversight\PHPBips\Bips\Bip0032', $bip);
    }
}
