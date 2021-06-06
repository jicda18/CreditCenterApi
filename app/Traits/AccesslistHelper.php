<?php

namespace App\Traits;

trait AccesslistHelper
{
    const MAX_IPV4 = 4294967296;

    protected function isValidIp(string $ip): bool
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    protected function isValidNetwork(string $network): bool
    {
        $arr = explode('/', $network);

        if (count($arr) !== 2) {
            return false;
        }

        $ip = $arr[0];

        if (!$this->isValidIp($ip)) {
            return false;
        }

        if (!$this->is_int($arr[1])) {
            return false;
        }

        $cidr = (int) $arr[1];

        return $cidr >= 0 && $cidr <= 32;
    }

    protected function isValidRange(string $range): bool
    {
        $arr = explode('-', $range);

        if (count($arr) !== 2) {
            return false;
        }

        if (!$this->isValidIp($arr[0]) || !$this->isValidIp($arr[1])) {
            return false;
        }

        return ip2long($arr[0]) <= ip2long($arr[1]);
    }

    protected function networkAddress(string $ip, int $cidr): string
    {
        return long2ip(
            ip2long($ip) & (static::MAX_IPV4 - pow(2, 32 - $cidr))
        );
    }

    protected function broadcastAddress(string $ip, int $cidr): string
    {
        return long2ip(
            (ip2long($ip) & (static::MAX_IPV4 - pow(2, 32 - $cidr))) + (pow(2, 32 - $cidr) - 1)
        );
    }
}
