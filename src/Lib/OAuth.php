<?php
declare(strict_types=1);

namespace BARSGroupTestTask\Lib;

use BARSGroupTestTask\Config;


final class OAuth
{
    static public function generationToken():string
    {
        return password_hash(
            (string)Request::getServer('REMOTE_ADDR').Config::AUTH_SALT,
            PASSWORD_DEFAULT
        );
    }

    static public function verificationToken(string $token):bool
    {
        return password_verify((string)Request::getServer('REMOTE_ADDR').Config::AUTH_SALT, $token);
    }

    static public function getTokenFromHeader(): string
    {
        return Request::getServer('HTTP_TOKEN', '');
    }
}