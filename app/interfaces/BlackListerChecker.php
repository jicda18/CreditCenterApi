<?php

namespace App\Interfaces;

use App\Models\Blacklist;
use Symfony\Component\HttpKernel\Exception\HttpException;

interface BlackListerChecker
{
    public function getIdentifier(): string;

    public function evaluate(string $key, int $seconds, int $limit): bool;

    public function getReason(): string;

    public function getSeconds(): int;

    public function getLimit(): int;

    public function getBlockSeconds(int $repeat = 1): int;

    public function blacklist($request, $key): Blacklist;

    public function buildException(int $retryAfter): HttpException;
}
