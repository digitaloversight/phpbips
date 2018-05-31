<?php

/**
 * This file is part of phpbips.
 * Bip32 for ECDP Key management using PHP.
 *
 * (c) Vincent A. Menard <root@aifs.io>
 */

namespace DigitalOversight\PHPBips\Bips;

use DigitalOversight\PHPBips\Base\EccBase;
use DigitalOversight\PHPBips\Base\Ecdsa;
use DigitalOversight\PHPBips\Util\Base58;
use DigitalOversight\PHPBips\Util\Endian;

class Bip0032 extends EccBase implements BipInterface
{
    const MIN_ENTROPY_LEN = 128;

    private $secret;

    private $chain;

    private $depth;

    private $index;

    private $fpr;

    private $public;

    /**
     * Configure the initial bip object.
     *
     * @param string $secret
     * @param string $chain
     */

    public function __construct(string $secret = '', string $chain = '')
    {
        parent::__construct();
        if (!empty($secret)) {
            $this->setSecret($secret);
        }
        
        if (!empty($chain)) {
            $this->setSecret($chain);
        }

        $this->depth = 0;
        $this->index = 1;
        $this->public = false;
        $this->fpr = null;
    }

    /**
     * Static initializers to create from entropy or external formats
     * Create a BIP32 using supplied entropy >= MIN_ENTROPY_LEN
     *
     * @param string entropy
     *
     * @throws \RuntimeException
     */

    public function fromEntropy(string $entropy = ''): void
    {
        if (!$entropy) {
            if (!extension_loaded('openssl')) {
                $entropy = random_bytes(MIN_ENTROPY_LEN/8);
            } else {
                $entropy = openssl_random_pseudo_bytes(MIN_ENTROPY_LEN/8);
            }
        }

        if (strlen($entropy) < MIN_ENTROPY_LEN/8) {
            throw new \RuntimeException("Provided entropy is too small");
        }

        $hmac = hash_hmac('sha512', $entropy, parent::getSeed());
        $this->secret = substr($hmac, 32, strlen($hmac)-1);
        $this->chain = substr($hmac, 0, 32);
    }

    /**
     * Create a BIP32Key by importing from extended private or public key string.
     * If public is True, return a public-only key regardless of input type.
     */

    public function fromExtendedKey(string $xkey, $public = false): void
    {
        $raw = Base58::decode($xkey);
        if (strlen($raw) != 78) {
            throw new \RuntimeException("Provided extended key has the wrong length");
        }

        $version = substr($xkey, 0, 4);
        if ($version == parent::getExPrivate()) {
            $this->setKeyType(false);
        } elseif ($version == parent::getExPublic()) {
            $this->setKeyType(true);
        } else {
            throw new \RuntimeException("Unkown extended key version");
        }

        $this->setDepth(ord($raw[4]));
        $this->setFpr(substr($raw, 5, 9));
        $this->setIndex(unpack("N", substr($raw, 9, 13)[0]));
        $this->setChain(substr($raw, 13, 45));
        $secret = substr($raw, 45, 78);

        if ($this->getKeyType() == false) {
            $this->setSecret(substr($secret, 1, strlen($secret)));
        } else {
            $lsb = ord($secret[0]) & 1;

            // Test this against python && intval()
            $x = Endian::string_to_int(substr($secret, 1, strlen($secret)));
            $ys = ($x**3+7) % SECP256K1_FIELD_ORDER;
            $y = parent::squareRootModP($ys, SECP256K1_FIELD_ORDER);

            if ($y & 1 != $lsb) {
                $y = SECP256K1_FIELD_ORDER-$y;
            }

            //point = ecdsa.ellipticcurve.Point(SECP256k1.curve, x, y)
            //secret = ecdsa.VerifyingKey.from_public_point(point, curve=SECP256k1)
        }
    }

    /**
     * Create a child key of index 'i'.
     * If the most significant bit of 'i' is set, then select from the
     * hardened key set, otherwise, select a regular child key.
     * Returns a BIP32 constructed with the child key parameters,
     * or None if i index would result in an invalid key.
     */

    public function CKDPriv(int $i)
    {
    }

    /**
     * Create a publicly derived child key of index 'i'.
     *  If the most significant bit of 'i' is set, this is
     *  an error.
     *  Returns a BIP32 constructed with the child key parameters,
     *  or None if index would result in invalid key.
     */

    public function CKDPub(int $i)
    {
    }

    /**
     * Create and return a child key of this one at index 'i'.
     * The index 'i' should be summed with BIP32_HARDEN to indicate
     * to use the private derivation algorithm.
     */

    public function childKey()
    {
    }

    /**
     * Return chain code as string
     */

    public function privateKey()
    {
    }

    /**
     * Return key identifier as string
     */

    public function chainCode()
    {
    }

    /**
     * Return key fingerprint as string
     */

    public function identifier()
    {
    }

    /**
     * Return compressed public key address
     */

    public function fingerprint()
    {
    }

    /**
     * Returns private key encoded for wallet import
     */

    public function walletImportFormat()
    {
    }

    /**
     * Return extended key as string, optionally Base58 encoded
     */

    public function extendedKey($private = true, $encoded = true)
    {
    }

    /**
     * Return the secret part of the key the generated
     */
 
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * Returns the chain part of the holded key
     */

    public function getChain(): ?string
    {
        return $this->chain;
    }

    private function setChain(string $chain): void
    {
        $this->chain = $chain;
    }

    public function setKeyType(bool $public = false)
    {
        $this->public = $public;
    }

    public function getKeyType(): bool
    {
        return $this->public;
    }

    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function setFpr(string $fpr): void
    {
        $this->fpr = $fpr;
    }

    public function getFpr(): string
    {
        return $this->fpr;
    }

    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    public function getIndex(): int
    {
        return $this->index;
    }
}
