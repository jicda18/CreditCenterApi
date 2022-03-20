<?php

namespace App\Classes\Abstracts;


use App\Exceptions\ToMuchRequestException;
use App\Interfaces\BlackListerChecker;
use App\Models\Blacklist;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ToMuchBlacklisterChecker implements BlackListerChecker
{
    /**
     * @var int
     */
    protected $_seconds;

    /**
     * @var int
     */
    protected $_limit;

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @var int
     */
    protected $_retryAfter = -1;

    /**
     * @var string
     */
    protected $_msg;

    public function __construct(int $seconds, int $limit, string $prefix, string $msg)
    {
        $this->_seconds = $seconds;
        $this->_limit = $limit;
        $this->_prefix = $prefix;
        $this->msg = $msg;
    }

    public function getIdentifier(): string
    {
        return "$this->_prefix$this->_seconds";
    }

    public function evaluate(string $key, int $seconds, int $limit): bool
    {
        $cache = "{$this->getIdentifier()}:{$key}";

        if (Cache::has($cache)) {
            $count = Cache::increment($cache);

            return $count > $limit;
        }

        Cache::put($cache, 1, $seconds);

        return false;
    }

    public function getReason(): string
    {
        return $this->msg;
    }

    public function getSeconds(): int
    {
        return $this->_seconds;
    }

    public function getLimit(): int
    {
        return $this->_limit;
    }

    public function getBlockSeconds(int $repeat = 1): int
    {
        if ($this->_retryAfter < 0) {
            $this->_retryAfter = pow($this->_seconds, $repeat);
        }

        return $this->_retryAfter;
    }

    public function blacklist($request, $key): Blacklist
    {
        $ip = request_ip();

        $repeat = $this->getRepeat($key);

        return Blacklist::create([
            'description' => $this->getReason(),
            'until_at' => date('Y-m-d H:i:s', time() + $this->getBlockSeconds($repeat)),
            'ip_range' => $ip,
        ]);
    }

    public function buildException(int $retryAfter): HttpException
    {
        $headers = [
            'Retry-After' => $retryAfter,
        ];

        return new ToMuchRequestException(
            $this->getReason(),
            null,
            $headers
        );
    }

    protected function getRepeat(string $key)
    {
        $cache = "{$this->getIdentifier()}:{$key}:repeat";

        if (Cache::has($cache)) {
            $count = Cache::increment($cache);

            return $count;
        }

        Cache::put($cache, 1);

        return 1;
    }
}
