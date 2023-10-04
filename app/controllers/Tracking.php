<?php

declare(strict_types = 1);
namespace app\controllers;
use app\controllers\Sidebar;
use app\models\Tracking as modelTracking;
use libs\controllers;
use libs\exception\TrackingException;
use Throwable;
use libs\exception\MessageException;

/**
 * Intermediary between main-model and main-view interactions with user session
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Tracking extends controllers{
    /**
     * @var     MessageException    $data for messages
     * @access  private
     */
    private MessageException $data;

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
        $this->data = new MessageException;
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
        $this->view->set('folioTemplate',self::templateTrackingTable($_SESSION['jobcode']));
        $this->view->set('sidebar',Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('filejs','Tracking');
        $this->view->set('name',$_SESSION['name']);
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Tracking');
        $this->view->render('Sidebar');
        $this->view->render('ScriptElements');
    }

    static public function templateTrackingTable($jobcode){
        $template = '';
        $i = 1;
        $folioTracking = modelTracking::getTrackingFolio($jobcode);

        if(empty($folioTracking)) return $template;

        foreach($folioTracking as $key){
            $status = empty($key['status']) ? 'Pendiente' : 'Resuelta';
            $template .= <<<EOD
            <tr>
                <th scope="#">{$i}</th>
                <td>{$key['folio']}</td>
                <td>{$key['petition']}</td>
                <td>{$key['description']}</td>
                <td>{$key['comment']}</td>
                <td>{$status}</td>
                <td>{$key['insertedDate']}</td>
            </tr>
        EOD;
        $i++;
        }

        return $template;
    }
    
    public function sendFolio(){
        try {
            $error = true;
            $template = '';
            $i = 1;
            
            foreach($_POST as $key => $value){
                $this->data->generateStatusMessage('status', $key, ['message' => 'correcto', 'status' => 'valid']);
                if(empty($value)){
                    $this->data->generateStatusMessage
                        ('status', $key, ['message' => 'Llenar campo obligatorio', 'status' => 'invalid']);
                    $error = false;
                }    
            }
            if(!$error) throw new TrackingException("El formulario contiene errores", 422);

            $alter = modelTracking::addFolio($_SESSION['jobcode'],$_POST['folio'],$_POST['petition'],$_POST['description']);
            if($alter < 1) throw new TrackingException("No se pudo guardar la informacion", 422);


            
            $folioTracking = modelTracking::getTrackingFolio($_SESSION['jobcode']);
    
            if(empty($folioTracking)) throw new TrackingException("No hay informacion", 422);
    
            foreach($folioTracking as $key){
                $status = empty($key['status']) ? 'Pendiente' : 'Resuelta';
                $template .= <<<EOD
                <tr>
                    <th scope="#">{$i}</th>
                    <td>{$key['folio']}</td>
                    <td>{$key['petition']}</td>
                    <td>{$key['description']}</td>
                    <td>{$key['comment']}</td>
                    <td>{$status}</td>
                    <td>{$key['insertedDate']}</td>
                </tr>
            EOD;
            $i++;
            }

            $this->data->generateMessage('tableFolioTmeplate',$template);

            $this->data->generateMessage(
                'exito',
                ['title' => 'Carga exitosa', 'message' => 'Folio enviado correctamente']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Send Folio Error',
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