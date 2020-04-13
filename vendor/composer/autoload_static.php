<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit35f6a3b570f6725bb5b619e494d80cd1
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\OptionsResolver\\' => 34,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\OptionsResolver\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/options-resolver',
        ),
    );

    public static $fallbackDirsPsr0 = array (
        0 => __DIR__ . '/..' . '/kzykhys/steganography/src',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit35f6a3b570f6725bb5b619e494d80cd1::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit35f6a3b570f6725bb5b619e494d80cd1::$prefixDirsPsr4;
            $loader->fallbackDirsPsr0 = ComposerStaticInit35f6a3b570f6725bb5b619e494d80cd1::$fallbackDirsPsr0;

        }, null, ClassLoader::class);
    }
}
