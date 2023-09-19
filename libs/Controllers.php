<?php

declare(strict_types = 1);

namespace libs;

use libs\Views;

class controllers{
    protected Views $view;
    
    public function __construct()
    {
        $this->view = new Views();
    }
}