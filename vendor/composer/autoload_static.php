<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit57239879fba418f229d8a164276e5fed
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit57239879fba418f229d8a164276e5fed::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit57239879fba418f229d8a164276e5fed::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
