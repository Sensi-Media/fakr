<?php

namespace Sensi\Fakr;

use Monolyth\Disclosure\Container;

$container = new Container;

$container->register(function (&$fakrRepository) {
    $fakrRepository = new Repository;
});

