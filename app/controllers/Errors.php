<?php

declare(strict_types = 1);

namespace app\controllers;

use libs\controllers;

/**
 * Builds the view for the Errors 
 * 
 * @package libs\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */

class Errors extends controllers{

    /**
     * Inherits the view callable configuration
     * 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Calls the view of Error
     * @param string Error message
     * 
     */
    public function index($message){

        $this->view->set('message',$message);
        $this->view->set('filejs','errors');
        $this->view->render('HeadElements');
        $this->view->render('Errors');
        $this->view->render('ScriptElements');
    }
}