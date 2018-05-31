<?php

/**
 * This file is part of phpbips.
 * BipAdapterFactory used to bind the bip file.
 *
 * (c) Vincent A. Menard <root@aifs.io>
 */

namespace DigitalOversight\PHPBips\Bips;

class BipAdapterFactory
{
    private static $forcedAdapter = null;
    
    /**
     * @param BipInterface $adapter
     */

    public static function forceAdapter(BipInterface $adapter = null)
    {
        self::$forcedAdapter = $adapter;
    }

    /**
     * @param string $bipVersion
     * @param bool $debug
     *
     * @throws \RuntimeException
     *
     * @return DebugDecorator|BipInterface|null
     */

    public static function getAdapter(string $bipVersion, bool $debug = false): BipInterface
    {
        if (self::$forcedAdapter !== null) {
            return self::$forcedAdapter;
        }
        $adapter = null;
        $bipClass = 'DigitalOversight\PHPBips\Bips\Bip'.$bipVersion;
        try {
            if (!class_exists($bipClass)) {
                throw new \RuntimeException();
            }
            $adapter = new $bipClass();
        } catch (\RuntimeException $e) {
            throw new \RuntimeException("Bip $bipVersion can not be loaded.");
        }

        return self::wrapAdapter($adapter, $debug);
    }

    /**
     * @param BipInterface $adapter
     * @param bool $debug
     * @return DebugDecorator|BipInterface
     */

    private static function wrapAdapter(BipInterface $adapter, bool $debug): BipInterface
    {
        if ($debug === true) {
            return new DebugDecorator($adapter);
        }
        return $adapter;
    }
}
