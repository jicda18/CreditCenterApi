<?php

namespace App\Traits;

trait AccesslistHelper
{
    protected $maxIpv4 = 4294967296;

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

        if (is_int($arr[1])) {
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
            ip2long($ip) & ($this->maxIpv4 - pow(2, 32 - $cidr))
        );
    }

    protected function broadcastAddress(string $ip, int $cidr): string
    {
        return long2ip(
            (ip2long($ip) & ($this->maxIpv4 - pow(2, 32 - $cidr))) + (pow(2, 32 - $cidr) - 1)
        );
    }

    /**
     * Convert range to network notation. return false if it isn't possible
     *
     * @param   string   var
     * @param   string   var
     * @return  false|string
     */
    protected function rangeToNetwork(string $start_ip, string $end_ip)
    {
        $ip1 = ip2long($start_ip);
        $ip2 = ip2long($end_ip) + 1;

        $cidr = 32 - log($ip1 ^ $ip2, 2);

        return ($cidr == (int) $cidr) ? long2ip($ip1) . "/{$cidr}" : false;
    }
}
