<?php

namespace Sensi\Fakr;

class View extends \View
{
    protected $template = 'Sensi/Fakr/template.html.twig';

    public function __construct()
    {
        parent::__construct();
        $this->inject(function ($fakrRepository) {});
        $this->mails = $this->fakrRepository->all();
    }
}

