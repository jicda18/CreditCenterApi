<?php

namespace App\Classes;

use App\Models\Blacklist;
use App\Models\Whitelist;
use App\Traits\AccesslistHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

abstract class AccesslistManagerCommand extends Command
{
    use AccesslistHelper;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * If this is a blacklist
     *
     * @var bool
     */
    protected $blacklisted;

    /**
     * The action to call
     *
     * @var string
     */
    protected $caller;

    /**
     * Available callers
     */
    protected $callers = [
        'list' => ['', 'List all entries.'],
        'find' => ['{ip : ip to search if is active in list. Format: x.x.x.x}', 'If exists, find a entry that match with ip.'],
        'add' => ['{ips* : ips to add in list. Format: x.x.x.x | x.x.x.x/y | x.x.x.x-y.y.y.y} {--S|second= : specified how many second this entry is valid. Use 0 or blank to unlimited.} {--D|description= : Description of the entries}', 'Add new entry per each ip.'],
        'remove' => ['{ids* : ids of entry}', 'Remove, if exists, each give entry.'],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(bool $blacklisted, string $caller)
    {
        $this->blacklisted = $blacklisted;

        $signature = ($blacklisted ? 'blacklist' : 'whitelist');

        if (!key_exists($caller, $this->callers)) {
            throw new Exception("Action $caller isn't a valid action for $signature", 1);
        }

        $arguments = $this->callers[$caller][0];
        $this->signature = trim("$signature:$caller $arguments");
        $this->description = $this->callers[$caller][1];

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->line('');
        return 0;
    }

    protected function list(): Collection
    {
        $list = $this->getList();

        return $list::all();
    }

    protected function find(): ?Collection
    {
        $ip = $this->argument('ip');
        $list = $this->getList();

        if (!$this->isValidIp($ip)) {
            $this->error('The ip must be a valid ip address.');
            return null;
        }

        return $list::searchIp($ip)->get();
    }

    protected function add(): ?Collection
    {
        $ips = $this->argument('ips');
        $time = (int) $this->option('time') ?? 0;
        $description = $this->option('description') ?? 'Add from command.';

        $list = $this->getList();

        $answers = collect();
        foreach ($ips as $key => $ip) {
            $validation = $this->validated($ip);

            if ($validation === '') {
                $answers->push("<error>$validation</error>");
            } else {
                $entry = $list::create([
                    'ip_range' => $ip,
                    'description' => $description,
                    'until_at' => $time === 0 ? null : Carbon::now()->addSeconds($time),
                ]);
            }
        }

        return $answers;
    }

    protected function remove(): ?Collection
    {
        $ids = $this->argument('ids');
        $list = $this->getList();

        $answers = collect();
        foreach ($ids as $key => $id) {
            $entry = $list::find($id);

            if ($entry === null) {
                $answers->push("<error>Entry wit id $id didn't found</error>");
            } else {
                $entry->delete();

                $answers->push("<comment>Entry wit id $id deleted</comment>");
            }
        }

        return $answers;
    }

    private function getList(): string
    {
        return $this->blacklisted ? Blacklist::class : Whitelist::class;
    }

    private function validated(string $ip): string
    {
        if ($ip === '') {
            return 'No ip gave.';
        } elseif (strpos($ip, '/') && !$this->isValidNetwork($ip)) {
            return "$ip isn't a valid network.";
        } elseif (strpos($ip, '-') && !$this->isValidRange($ip)) {
            return "$ip isn't a valid range.";
        } elseif ($this->isValidIp($ip)) {
            return "$ip isn't a valid ip.";
        }

        return '';
    }
}
