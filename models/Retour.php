<?php

namespace distantnative\Retour;

use Kirby\Data\Data;
use Kirby\Toolkit\Dir;
use Kirby\Toolkit\F;

class Retour
{
    protected $logs;
    protected $redirects;
    protected $stats;
    protected $system;

    public static $dir;

    public function flush(): void
    {
        Dir::remove(static::$dir);
    }

    public function logs(): Logs
    {
        return $this->log = $this->logs ?? new Logs;
    }

    public function process()
    {
        $tmp   = [];
        $files = static::$dir . '/.*.tmp';

        foreach (glob($files) as $file) {
            $tmp[] = Data::read($file, 'yaml');
            F::remove($file);
        }

        if (empty($tmp) === false) {
            $this->logs()->add($tmp);
            $this->stats()->count($tmp);
            $this->redirects()->hit(array_filter($tmp, function ($x) {
                return $x['status'] === 'redirected';
            }));
        }
    }

    public function redirects(): Redirects
    {
        return $this->redirects = $this->redirects ?? new Redirects;
    }

    public function stats(): Stats
    {
        return $this->stats = $this->stats ?? new Stats;
    }

    public static function store(string $path, string $status, string $pattern = null)
    {
        $file = static::$dir . '/.' . md5($path) . '.' . time() . '.tmp';

        Data::write($file, [
            'path'     => $path,
            'referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            'status'   => $status,
            'pattern'  => $pattern,
            'date'     => date('Y-m-d H:i')
        ], 'yaml');
    }

    public function system(): System
    {
        return $this->system = $this->system ?? new System;
    }
}
