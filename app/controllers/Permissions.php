<?php

declare(strict_types=1);

namespace app\controllers;

use Throwable;
use libs\controllers;
use app\models\Permissions as modelPermissions;
use libs\exception\MessageException;
use libs\exception\PermissionsException;

/**
 * Performs users permission management
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Permissions extends controllers
{
    /**
     * @var     MessageException    $data for messages
     * @access  private
     */
    private MessageException $data;

    /**
     * Prepares messages compilare
     * @access  public
     * @global  MessageException    $data   Instance message method
     */
    public function __construct()
    {
        $this->data = new MessageException;
        parent::__construct();
    }

    public function index(): void
    {
        if(empty($_SESSION)){
            header('location: Session');
        }
        $this->view->set('searchRequest',self::searchRequest());
        $this->view->set('sidebar',Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('filejs','Permissions');
        $this->view->set('name',$_SESSION['name']);
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Permissions');
        $this->view->render('Sidebar');
        // $this->view->render('Footer');
        $this->view->render('ScriptElements');
    }

    private static function searchRequest(): string
    {
        $request['users'] = modelPermissions::searchRequests();
        $profile = modelPermissions::searchProfile();
        $request['client'] = modelPermissions::searchClient();

        $request['profile'] = array_map(function ($profile){
            if($profile['kind']<= 3){
                $profile['needsgroup'] = true;
            }
            if($profile['id'] === '6'){
                $profile['needsmanager'] = true;
            }
            return $profile;
        },$profile);
        
        $request = json_encode($request);
        
        $style = <<<EOD
            <script>
                let request = {$request};
            </script>
        EOD;

        return $style;
    }

    public function getManager(): void
    {
        try {
            extract($_POST);
            $manager = modelPermissions::searchManager((int)$Client);

            if(empty($manager)) {
                throw new PermissionsException("No existe ningun encargado para este grupo", 428);
            }
            
            $this->data->generateMessage('managers',$manager);
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Get Manager error',
                    $e->getMessage(),
                    (int)$e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }
    }

    public function enableJobcodes(){

        try {
            $requestData = modelPermissions::requestData($_POST['jobcode']);

            modelPermissions::enableUser($requestData,$_POST);
            modelPermissions::enableUserInfo($requestData,$_POST);
            modelPermissions::requestStatus($_POST['jobcode']);

            $this->data->generateMessage(
                'exito',
                ['title' => 'Registro exitoso', 'message' => 'Las claves se han habilitado']
            );
            $this->data->generateMessage('jobcode',$_POST['jobcode']);
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Enable Jobcodes error',
                    $e->getMessage(),
                    (int)$e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }
    }
}
