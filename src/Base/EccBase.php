<?php

/**
 * Base class used to create and manipulate standard
 * crypto currency elements. This class is a basic usage
 * of the phpecc functions.
 */

namespace DigitalOversight\PHPBips\Base;

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Math\NumberTheory;

use DigitalOversight\PHPBips\Util\Base58;

class EccBase
{
    private $seed;          // hash function seed

    private $exPrivate;     // version prefix of extended private key

    private $exPublic;      // version prefix of extended public key

    private $privateKey;    // main private key holded

    /* SECP256K1 Field order */

    const SECP256K1_FIELD_ORDER = 115792089237316195423570985008687907853269984665640564039457584007908834671663;

    public function __construct(string $curve = 'secp256k1', string $seed = 'Bitcoin seed')
    {
        // constructor default are bitcoin network values
        
        $this->setSeed($seed);
        $this->setExPrivate('0488ade4');
        $this->setExPublic('0488b21e');
    }

    /**
     * Create a new wallet private key
     */

    public function createPKey(): void
    {
        $generator = EccFactory::getNistCurves()->generator256k1();
        $this->privatekey = $generator->createPrivateKey();
    }

    /**
     * Get the DER encoded private key
     */

    public function getDerPrivateKey(int $base = 0): string
    {
        $adapter = EccFactory::getAdapter();
        $derSerializer = new DerPrivateKeySerializer($adapter);
        $der = $derSerializer->serialize($this->privateKey);

        if ($base = 64) {
            return base64_encode($der);
        } elseif ($base = 58) {
            return Base58::encode($der);
        }

        return $der;
    }

    public function squareRootModP($a, $p)
    {
        $theory = EccFactory::getAdapter()->getNumberTheory();
        return $theory->squareRootModP($a, $p);
    }

    /**
     * Get the PEM encoded private Key
     */

    public function getPemPrivateKey(): string
    {
        $adapter = EccFactory::getAdapter();
        $derSerializer = new DerPrivateKeySerializer($adapter);
        $der = $derSerializer->serialize($this->private);

        $pemSerializer = new PemPrivateKeySerializer($derSerializer);
        $pem = $pemSerializer->serialize($this->privateKey);

        return $pem;
    }

    public function setSeed(string $seed): void
    {
        $this->seed = $seed;
    }

    public function getSeed(): string
    {
        return $this->seed;
    }

    /**
     *
     */

    public function setExPrivate(string $exPrivate): void
    {
        if (ctype_xdigit($exPrivate)) {
            $this->exPrivate = $exPrivate;
        } else {
            throw new \InvalidArgumentException("The exPrivate value is not hex format");
        }
    }

    public function getExPrivate(bool $decode = false): string
    {
        if ($decode) {
            return hex2bin($this->exPrivate);
        }

        return $this->exPrivate;
    }

    public function setExPublic(string $exPublic): void
    {
        if (ctype_xdigit($exPublic)) {
            $this->exPublic = $exPublic;
        } else {
            throw new \InvalidArgumentException("The exPublic value is not hex format");
        }
    }

    public function getExPublic(bool $decode = false): string
    {
        if ($decode) {
            return hex2bin($this->exPublic);
        }

        return $this->exPublic;
    }
}
