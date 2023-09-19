<?php

declare(strict_types = 1);
namespace app\controllers;
use app\controllers\Sidebar;
use libs\controllers;

/**
 * Intermediary between main-model and main-view interactions with user session
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Main extends controllers{

    /**
     * Constructor
     * Inherits the view callable configuration
     * @access public
     * @return void
     * @throws 
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validates the user session and defines the interface 
     * @access public
     * @global $_SESSION Contains user configurations
     * @throws DataBaseException If an error occurs during the data base interaction
     * @return void
     */
    public function index(): void
    {
        if(empty($_SESSION)){
            header('location: Session');
        }
        $this->view->set('sidebar',Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('filejs','main');
        $this->view->set('name',$_SESSION['name']);
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Main');
        $this->view->render('Sidebar');
        $this->view->render('Footer');
        $this->view->render('ScriptElements');
    }
}
