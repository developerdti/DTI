<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Schedule as modelSchedule;
use libs\controllers;
use libs\exception\MessageException;
use libs\exception\ScheduleException;
use Throwable;

/**
 * Performs updates for notes and productivity followup
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Schedule extends controllers
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

    /**
     * Validates the user session and defines the interface
     * @access  public 
     * @global  array $_SESSION Contains user configurations
     * @throws  DataBaseException If an error occurs during the data base interaction
     * @return  void
     */
    public function index(): void
    {
        if (empty($_SESSION)) {
            header('Location: Main');
        }
        $this->view->set('graphic', $this->createGraphic($_SESSION['jobcode']));
        $this->view->set('sidebar', Sidebar::creatSidebar($_SESSION['kind'],$_SESSION['group']));
        $this->view->set('name', $_SESSION['name']);
        $this->view->set('filejs', 'Schedule');
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('Schedule');
        $this->view->render('Sidebar');
        $this->view->render('Footer');
        $this->view->render('ScriptElements');
    }

    /**
     * Defines configuration and graphic data
     * @access  public
     * @throws  DataBaseException    If an error occurs during the data base interaction
     * @param   string   $jobcode    unique user codes
     * @return  string   graphic template
     */
    public function createGraphic(string $jobcode): string
    {
        try {
            $template = '';
            $pp['before'] = modelSchedule::ppMonthBefore($jobcode);
            $pp['current'] = modelSchedule::ppCurrentMonth($jobcode);
            $pp['day'] = modelSchedule::ppCurrentDay($jobcode);

            foreach ($pp as $key => $value) {
                $value['amount'] = isset($value['amount']) ? $value['amount'] : 0;
                $value['capital'] = isset($value['capital']) ? $value['capital'] : 0;
                $value['bankPayment'] = isset($value['bankPayment']) ? $value['bankPayment'] : 0;
                $name = $key;
                $template .= <<<EOD
                        <script>
                            const {$name}Amount = {$value['amount']};
                            const {$name}Capital = {$value['capital']};
                            const {$name}Bankpayment = {$value['bankPayment']};
                        </script>
                    EOD;
            }

            return $template;
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    /**
     * Uploads new message associated to a customer folio
     * @access  public
     * @global  MessageException    $data   instance for massage generator
     * @global  array               $_POST  contains customer folio and note-message
     * @return  void
     */
    public function notes(): void
    {
        try {
            $this->validatefolio($_POST['folio']);
            extract($_POST);
            modelSchedule::saveNote($folio, $comment, $_SESSION['jobcode']);
            $this->data->generateMessage('exito', ['title' => 'Exito', 'message' => 'Se registro correctamente la nota']);
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Save Notes Error',
                    $e->getMessage(),
                    $e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }
    }

    /**
     * Validates folio form field, before uploading message
     * @access  private
     * @throws  ScheduleException           If a field does not comply with Specifications
     * @global  MessageException    $data   instance for message generator
     * @param   string              $folio  customer folio
     * @return  void
     */
    private function validatefolio(string $folio): void
    {
        try {
            if (empty($folio)) {
                throw new ScheduleException("Es necesario ingresar un folio", 422);
            }
            $this->data->generateStatusMessage('status', 'folio', ['message' => 'correcto', 'status' => 'valid']);
        } catch (Throwable $e) {
            if ($e->getPrevious()) {
                throw new ScheduleException($e->getMessage(), $e->getCode(), $e->getPrevious());
            }
            $this->data->generateStatusMessage('status', 'folio', ['message' => $e->getMessage(), 'status' => 'invalid']);
            throw new ScheduleException("El formulario contiene errores", 422);
        }
    }

    /**
     * Searchs for all of notes that belongs to the session user
     * @access  public
     * @throws  DataBaseException   If an error occurs during the data base interaction
     * @global  array   $_SESSION   User session configurations
     * @return  void
     */
    public function getNotes(): void
    {
        $template = '';
        $i = 1;
        try {
            $notes =  modelSchedule::getNotes($_SESSION['jobcode']);
            $modalHead = <<<EOD
                <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Comentario</th>
                        <th scope="col">Fecha</th>
                        <th class="delete_thNote" scope="col">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider" >
            EOD;
            $endTable = '</tbody></table>';

            foreach ($notes as $key) {
                $template .= <<<EOD
                    <tr>
                        <th scope="row">{$i}</th>
                        <td>{$key["folio"]}</td>
                        <td>{$key["comment"]}</td>
                        <td>{$key["insertedDate"]}</td>
                        <td class="delete_note"><button type='button' class='btn_delete__notes' id='{$key["id"]}'><i class="bi bi-trash3-fill"></i></button></td>
                    </tr>
                EOD;
                $i++;
            }
            $this->data->generateMessage('notes',$modalHead.$template.$endTable);
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Get notes error',
                    $e->getMessage(),
                    $e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }
    }

    /**
     * Generates table template for user session follow-Up
     * @access  public
     * @throws  ScheduleException   If a field does not comply with Specifications
     * @global  array   $_SESSION   User session configurations
     * @return  void
     */
    public function getFollowUp(): void
    {
        $rowtemplate = '';
        $i = 1;
        try {
            $followup =  modelSchedule::getfollowUp($_SESSION['jobcode']);
            $modalHead = <<<EOD
                <table class="table table-bordered table-hover table-responsive">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Folio</th>
                        <th scope="col">Fecha de accion</th>
                        <th scope="col">Monto</th>
                        <th scope="col">Fecha de pago</th>
                        <th scope="col">RMT</th>
                        <th scope="col">Pago realizado</th>
                        <th scope="col">Fecha de pago en banco</th>
                        <th scope="col">Capital</th>
                        <th scope="col">Tipo de negociacion</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
            EOD;
            $endTable = "</tbody></table>";

            foreach ($followup as $key) {
                $status =
                    ($key["promise"] === "Si") ? "success" : ($key["promise"] === "No" ? "danger" : "warning");

                $rowtemplate .= <<<EOD
                <tr class="table-{$status}">
                    <th scope="row">{$i}</th>
                    <td><p>{$key["folio"]}</p></td>
                    <td><p>{$key["actionDate"]}</p></td>
                    <td><input value="{$key["amount"]}" type="number" disabled></td>
                    <td><p>{$key["paymentDay"]}</p></td>
                    <td>{$this->findRMT($key["promise"])}</td>
                    <td><input value="{$key["bankPayment"]}" type="number" disabled></td>
                    <td><input value="{$key["bankPaymentDay"]}" type="date" disabled></td>
                    <td><p>{$key["capital"]}</p></td>
                    <td>{$this->findBusinessType($key["businessType"])}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-followup">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                    </td>
                </tr>
                EOD;
                $i++;
            }
            $this->data->generateMessage('table', $modalHead . $rowtemplate . $endTable);
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Get follow up Error',
                    $e->getMessage(),
                    $e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }
    }

    /**
     * Reorganize HTML OPTIONS from SELECT template for RMT
     * @access  private
     * @param   string  $promise  RMT status
     * @return  string  HTML SELECT template
     */
    private function findRMT(string|null $promise): string
    {
        $rmt = '';
        $select = [
            '<option selected>ind</option>',
            '<option>Si</option>',
            '<option>No</option>',
        ];

        if (!isset($promise)) {
            unset($select[0]);
            array_unshift($select, '<option selected>ind</option>');
        }

        foreach ($select as $key => $value) {
            if (preg_match('/' . $promise . '/i', $value) && (isset($promise))) {
                $value = '<option selected>' . $promise . '</option>';
            }
            $rmt .= $value;
        }

        $result = '<select class="promiseSelect" disabled>' . $rmt . '</select>';
        return $result;
    }

    /**
     * Reorganize HTML OPTIONS from SELECT template for business type
     * @access  private
     * @param   string  $business  business type
     * @return  string  HTML SELECT template
     */
    private function findBusinessType(string|null $business): string
    {
        $businessType = '';
        $select = [
            '<option selected>Seleccionar</option>',
            '<option>Parcial</option>',
            '<option>Minimo</option>',
            '<option>Reestructura</option>',
            '<option>Liquidacion</option>'
        ];

        if (!isset($business)) {
            unset($select[0]);
            array_unshift($select, '<option selected>Seleccionar</option>');
        }

        foreach ($select as $key => $value) {
            if (preg_match('/' . $business . '/i', $value) && (isset($business))) {
                $value = '<option selected>' . $business . '</option>';
            }
            $businessType .= $value;
        }

        $result = '<select disabled>' . $businessType . '</select>';
        return $result;
    }

    /**
     * Upload follow up of user session
     * @access  public
     * @throws  DataBaseException   If an error occurs during the data base interaction
     * @return  void
     */
    public function saveFollowUp(): void
    {
        try {
            $json = json_decode(file_get_contents('php://input'));
            modelSchedule::saveFollowUp($json);
            $this->data->generateMessage('success','Actualizacion exitosa');
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Save follow up Error',
                    $e->getMessage(),
                    $e->getCode(),
                    get_class($e),
                    $previous
                )
            );
            http_response_code($e->getCode());
        } finally {
            echo json_encode($this->data->getdata());
        }

    }

    public function deleteNote(): void
    {
        try {
            $json = json_decode(file_get_contents('php://input'));
            modelSchedule::delete_note((int)$json->id);

            $this->data->generateMessage('success','Nota eliminada');
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Save follow up Error',
                    $e->getMessage(),
                    $e->getCode(),
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
