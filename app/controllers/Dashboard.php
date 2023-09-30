<?php

declare(strict_types = 1);
namespace app\controllers;
use app\controllers\Sidebar;
use app\models\Dashboard as modelDashboard;
use libs\controllers;
use libs\exception\DashboardException;
use Throwable;
use libs\exception\MessageException;

/**
 * Intermediary between main-model and main-view interactions with user session
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Dashboard extends controllers{
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
        $this->view->set('dashboardTemplate',self::templateDashboard($_SESSION['group']));
        $this->view->set('sidebar',Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('filejs','Dashboard');
        $this->view->set('name',$_SESSION['name']);
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Dashboard');
        $this->view->render('Sidebar');
        $this->view->render('ScriptElements');
    }

    public static function templateDashboard($group) :string
    {
        $template = '';
        $section = modelDashboard::getSections($group);
        foreach($section as $key){
            $template .= <<<EOD
                <button class="nav-link collapseDashboard__buttonPill" data-bs-toggle="pill" 
                value="{$key['id']}" >{$key['name']}</button>
            EOD;
        }

        return $template;
    }

    public function addImage(): void
    {
        try {
            $templateImage = '';
            $this->data->generateStatusMessage('status', 'searchImage', ['message' => 'correcto', 'status' => 'valid']);
            if(empty($_FILES['searchImage']['size'])){
                $this->data->generateStatusMessage
                    ('status', 'searchImage', ['message' => 'Seleccionar imagen', 'status' => 'invalid']);
                throw new DashboardException("El formulario contiene errores", 422);
            }
            extract($_POST);//
            $sectionInfo = modelDashboard::sectionInfo((int) $sectionID);
            extract($sectionInfo);

            $temp = $_FILES['searchImage']['tmp_name'];
            $file = $_FILES["searchImage"]["name"];
            $url_insert = IMAGE_PATH_LOCAL.'/'.$location.'/'.$file;

            modelDashboard::addImage((int) $sectionID, $file, $description);

            if(!move_uploaded_file($temp,$url_insert)){
                throw new DashboardException("No se pudo guardar la imagen", 428);
            };

            $images = modelDashboard::imageInfo((int) $sectionID);
            foreach($images as $key){
                $imagePATH = IMAGE_PATH.'/'.$location.'/'.$key['name'];
                $templateImage .= <<<EOD
                    <div class="collapseDashboard__divImage">
                        <button class="deleteImage__Button displayNone" value="{$key['id']}"><i class="bi bi-x"></i></button>
                            <p>{$key['description']}</p> 
                        <img src="{$imagePATH}">
                    </div>
                EOD;
            }
            $this->data->generateMessage('templateImage',$templateImage);
            $this->data->generateMessage(
                'exito',
                ['title' => 'Carga exitosa', 'message' => 'Imagen cargada con exito']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Add Image error',
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

    public function getTemplateSection(){
        $json = json_decode(file_get_contents('php://input'));
        $template = '';
        $image = '';
        
        $sectionInfo = modelDashboard::sectionInfo((int) $json[0]);
        extract($sectionInfo);//name,location
        
        $imageInfo = modelDashboard::imageInfo((int) $json[0]);
        // extract($imageInfo);//id,name,description

        
        foreach($imageInfo as $key){
            $imagePATH = IMAGE_PATH.'/'.$location.'/'.$key['name'];
            $image .= <<<EOD
                <div class="collapseDashboard__divImage">
                    <button class="deleteImage__Button displayNone" value="{$key['id']}"><i class="bi bi-x"></i></button>
                        <p>{$key['description']}</p> 
                    <img src="{$imagePATH}">
                </div>
            EOD;
        }
        
        $template = <<<EOD
            <div class="collapseDashboard__deployment-header" id='collapseDashboard--deploymentHeader'>
                {$image}
            </div>
            <div class="collapseDashboard__deployment-body">
                <form id="form__collapseDashboard" name="formcollapseDashboard" class="collapseDashboard__form">
                    <fieldset>
                        <input type="hidden" name="sectionID" value="{$json[0]}">
                        <div class="collapseDashboard__divDescription input-group">
                            <span class="input-group-text fs-3">
                            <i class="bi bi-fonts"></i>
                            </span>
                            <input id="collapseDashboard--Description" type="text" name="description" class="form-control fs-3" placeholder="Titulo">
                        </div>
                        <div class="collapseDashboard__divSearchImage input-group">
                            <input id="collapseDashboard--SearchImage" type="file" name="searchImage" class="form-control fs-3">
                            <label for="collapseDashboard--SearchImage" class="input-group-text fs-3">
                            <i class="bi bi-file-earmark-image"></i> Buscar Imagen
                            </label>
                        </div>
                        <div class="collapseDashboard__buttons">
                            <button type="button" class="button--collapseDashboard" id="button__collapseDashboard">Subir imagen</button>
                            <button type="button" class="button--collapseDashboard" id="button__deleteImage">Eliminar imagen</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        EOD;
        echo json_encode($template);
    }

    public function deleteImage(){
        
        try {
            $json = json_decode(file_get_contents('php://input'));
            $delCount = modelDashboard::deleteImage((int) $json[0]);
            if(empty($delCount)){
                throw new DashboardException("No se pudo eliminar la imagen", 428);
            };
            $this->data->generateMessage(
                'exito',
                ['title' => 'Eliminacion Exitosa', 'message' => 'Imagen Eliminada correctamente']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Add Image error',
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