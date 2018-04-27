<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Injector;
use Quibble\Query\SelectException;
use PDO;

class Repository
{
    use Injector;

    private $sensiAdapter;

    public function __construct()
    {
        $this->inject(function ($sensiAdapter) {});
    }

    public function all() : array
    {
        try {
            return $this->sensiAdapter->selectFrom('fakr_inbox')
                ->orderBy('datecreated DESC')
                ->fetchAll(PDO::FETCH_CLASS, Model::class);
        } catch (SelectException $e) {
            return [];
        }
    }
}

