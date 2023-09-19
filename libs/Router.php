<?php
declare(strict_types = 1);

namespace libs;

use libs\exception\RequireException;
use Throwable;
use app\controllers\Errors;

class Router{

    const STR_NAMESPACE = 'app\controllers\\';

    private string|object $controller;

    private array $URL;

    private string $method = 'index';

    private array $atributes = [];

    public function __construct()
    {
        try {
            if(!isset($_GET['url'])){
                throw new RequireException("No existe esta URL",404);
            }
            $this->URL = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
            
            $this->controller = ucfirst($this->URL[0]);

            $this->method = $this->URL[1] ?? $this->method;
            
            

            if(!file_exists(CONTROLLERS_PATH.'/'.$this->controller.'.php')){
                throw new RequireException("Esta pagina no existe",404);
            }

            $namespace = self::STR_NAMESPACE.$this->controller;

            if(!method_exists($namespace,$this->method)){
                throw new RequireException("No existe este metodo",404);
            }

            unset($this->URL[0],$this->URL[1]);
            $this->atributes =  $this->URL ? array_values($this->URL) : $this->atributes;

            $this->controller = new $namespace;
            call_user_func([$this->controller,$this->method],$this->atributes);
        } catch (Throwable $e) {
            echo $e->getMessage().$e->getCode();
            echo json_encode(($e->getMessage().$this->method).$e->getprevious());
            // http_response_code($e->getCode());
        }
    }
}


