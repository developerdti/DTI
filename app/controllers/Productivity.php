<?php

declare(strict_types = 1);
namespace app\controllers;
use app\controllers\Sidebar;
use app\models\Productivity as modelProductivity;
use libs\controllers, app\controllers\Support;
use libs\exception\ProductivityException;
use Throwable;
use libs\exception\MessageException;

/**
 * Intermediary between main-model and main-view interactions with user session
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Productivity extends controllers{
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
        // $this->view->set('folioTemplate',self::templateTrackingTable($_SESSION['jobcode']));
        // $this->view->set('manualDailingTemplate',self::templateManualDailing($_SESSION['jobcode']));
        $this->view->set('sidebar',Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('filejs','Productivity');
        $this->view->set('name',$_SESSION['name']);
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Productivity');
        $this->view->render('Sidebar');
        $this->view->render('ScriptElements');
    }

    public function addMarkingFile(){
        try {
            $error = false;
            $this->data->generateStatusMessage('status', 'manualMarkingFile', ['message' => 'correcto', 'status' => 'valid']);

            if(empty($_FILES['manualMarkingFile']['size'])){
                $this->data->generateStatusMessage('status', 'manualMarkingFile', ['message' => 'Archivo Vacio', 'status' => 'invalid']);
                $error = true;
            }
            
            if($error) throw new ProductivityException("El formulario contiene errores", 422);

            $file = self::readFileCSV($_FILES['manualMarkingFile']['tmp_name']);
            array_shift($file);

            $idSupervisorReference = modelProductivity::getSupervisorReference($_SESSION['jobcode'],$_SESSION['group']);
            if(empty($idSupervisorReference)){
                modelProductivity::addSupervisorReference($_SESSION['jobcode'],$_SESSION['group']);
                $idSupervisorReference = modelProductivity::getSupervisorReference($_SESSION['jobcode'],$_SESSION['group']);
            }

            modelProductivity::addmarkingFile($file,(int) $idSupervisorReference['id']);

            $this->data->generateMessage('tableFolioTmeplate',$file);
            $this->data->generateMessage(
                'exito',
                ['title' => 'Carga exitosa', 'message' => 'Archivo subido correctamente']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Add Marking File Error',
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

    private static function readFileCSV(string $fileTemp): array
    {
        $openFile = fopen("".$fileTemp."","r");
        while ($data = fgetcsv ($openFile, 1000, ",")) {
            foreach($data as $key => $value){
                if($key == 0 && isset($value)){
                    $dataInfo[$key]= strtolower($value);
                }else{
                    $dataInfo[$key]= $value;
                }
            }
            $file[] = $dataInfo;
            }
        fclose($openFile);

        return $file;
    } 
}