<?php

/**
 * This file is part of phpbips.
 * Create Bips, Eccbase and Ecdsa objects.
 *
 * (c) Vincent A. Menard <root@aifs.io>
 */

namespace DigitalOversight\PHPBips;

use DigitalOversight\PHPBips\Bips\BipInterface;
use DigitalOversight\PHPBips\Bips\BipAdapterFactory;
use DigitalOversight\PHPBips\Base\EccBase;

class BipFactory
{

    /**
     * Selects and creates the bip
     *
     * @param int $bip The integer bip number
     * @param bool $debug [optional] Set to true to get a trace of all mathematical operations
     *
     * @throws \InvalidArgumentException
     * @return BipInterface
     */

    public static function getBip(int $bip, bool $debug = false): BipInterface
    {
        if ($bip <= 0 || $bip > 9999) {
            throw new InvalidArgumentException("Bip version is invalid");
        }
        $bip = str_pad($bip, 4, "0", STR_PAD_LEFT);

        return BipAdapterFactory::getAdapter($bip, $debug);
    }

    /**
     * Get the Ecc base object
     *
     * @return EccBase
     */

    public static function getEccBase(): EccBase
    {
        return new EccBase();
    }
}
