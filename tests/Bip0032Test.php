<?php

namespace DigitalOversight\PHPBips;

use DigitalOversight\PHPBips\Tests;
use DigitalOversight\PHPBips\BipFactory;
use DigitalOversight\PHPBips\Bips\BipInterface;
use DigitalOversight\PHPBips\Util\Base58;
use PHPUnit\Framework\TestCase;

class Bip32Tests extends TestCase
{
    public function testInstanceOf(): void
    {
        $bip = BipFactory::getBip(32);
        $this->assertInstanceOf('DigitalOversight\PHPBips\Bips\Bip0032', $bip);
    }

    /**
     * Test that the chain is 32 bytes
     */

    public function testChainFromEntropy(): void
    {
        $bip = BipFactory::getBip(32);
        $bip->fromEntropy('000102030405060708090a0b0c0d0e0f');
        $this->assertEquals(strlen($bip->getChain()), 32);
    }

    /**
     * Test generation from provided key
     */

    public function testChainFromKey(): void
    {
        $key = 'xprv9s21ZrQH143K2k6wJkw83LRrqpCBZfHA4GLhWtn6Gjur3UKzweWPX6fgVYmivQBEhgicWvrJUYGhkAJKy5UJy9PhxHBf8sD8AEb7Vkwhgn8';
        $bip = BipFactory::getBip(32);
        $bip->fromExtendedKey($key);
        $this->assertEquals(strlen($bip->getChain()), 32);
    }
}
