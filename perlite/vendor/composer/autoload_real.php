<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit49760f5a39ccaa8600b88f06b4cfa48e
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit49760f5a39ccaa8600b88f06b4cfa48e', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit49760f5a39ccaa8600b88f06b4cfa48e', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit49760f5a39ccaa8600b88f06b4cfa48e::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
