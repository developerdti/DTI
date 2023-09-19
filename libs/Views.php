<?php

declare(strict_types = 1);

namespace libs;

class Views{

    private array $data = [];

    public function render(string $viewName) : void{
        $path = VIEWS_PATH.'/'.$viewName.'.php';

        if(!file_exists($path)){
            echo "no existe la vista";
            return;
        }

        ob_start();
        if(!empty($this->data)){
            extract($this->data);
        }

        include $path;

        echo ob_get_clean();
    }

    public function set(string $varName, string $value) : void
    {
        $this->data[$varName] = $value;
    }
}