<?php

declare(strict_types=1);

namespace app\controllers;

use libs\controllers;
use app\models\User as modelUser;
use libs\exception\UserException;
use Throwable, libs\exception\MessageException;

/**
 * Performs user configurations
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class User extends controllers
{
    /**
     * @var     MessageException    $data   for messages
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
     * @global  array $_SESSION     Contains user configurations
     * @throws  DataBaseException   If an error occurs during the data base interaction
     * @return  void
     */
    public function index(): void
    {
        if (empty($_SESSION)) {
            header('Location: Main');
        }
        $this->view->set('sidebar', Sidebar::creatSidebar($_SESSION['kind'], $_SESSION['group']));
        $this->view->set('name', $_SESSION['name']);
        $this->view->set('filejs', 'User');
        $this->view->render('HeadElements');
        $this->view->render('Navbar');
        $this->view->render('User');
        $this->view->render('Sidebar');
        $this->view->render('Footer');
        $this->view->render('ScriptElements');
    }

    /**
     * Calls and compyle all of the validations one by one
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  UserException           If a field does not satisfy the specifications
     * @global  MessageException    $data       Stores error/success message
     * @global  array               $_POST      Form fields
     * @global  array               $_SESSION   User session params
     * @return  void
     */
    public function changePassword()
    {
        try {
            $_POST['jobcode'] = $_SESSION['jobcode'];

            $this->validateFormFields($_POST);

            $savepass = modelUser::savePassword(
                password_hash($_POST['newpassword'], PASSWORD_DEFAULT, ['cost' => 10]),
                $_SESSION['jobcode']
            );
            $this->data->generateMessage(
                'exito',
                ['title' => 'Registro exitoso', 'message' => 'Su contraseña a sido actualizada']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'Change Password Error',
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
     * Calls and compyle all of the validations one by one
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  UserException  If a field does not satisfy the specifications
     * @global  MessageException    $data       Stores error/success message
     * @param   array               $formdata   Form fields
     * @return  void
     */
    private function validateFormFields(array $formData): void
    {
        $errors = false;
        foreach ($formData as $key => $value) {
            try {
                if ($key !== 'jobcode') {
                    call_user_func_array([$this, 'validate' . $key], [$formData]);
                    $this->data->generateStatusMessage('status', $key, ['message' => 'correcto', 'status' => 'valid']);
                }
            } catch (Throwable $e) {
                $errors = true;
                if ($e->getPrevious()) {
                    throw new UserException($e->getMessage(), $e->getCode(), $e->getPrevious());
                }
                $this->data->generateStatusMessage('status', $key, ['message' => $e->getMessage(), 'status' => 'invalid']);
            }
        }
        if ($errors) {
            throw new UserException("El formulario contiene errores", 422);
        }
    }

    /**
     * Valid Old password field
     * @access  private
     * @throws  UserException           If an error occurs during the data base interaction
     * @throws  UserException  If the search is empty or does not satisfy requirements
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validateoldpassword(array $formData): void
    {
        $USER = modelUser::valideOldPassword($formData['jobcode']);
        if (empty($formData['oldpassword'])) {
            throw new UserException("Es necesario ingresar tu contraseña", 422);
        }
        if (!isset($USER['password'])) {
            throw new UserException("La contraseña ingresada no es valida", 422);
        }
        if (!password_verify($formData['oldpassword'], $USER['password'])) {
            throw new UserException("La contraseña ingresada es incorrecta", 422);
        }
    }

    /**
     * Valid New password field
     * @access  private
     * @throws  UserException           If an error occurs during the data base interaction
     * @throws  UserException  If the search is empty or does not satisfy requirements
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatenewpassword(array $formData): void
    {
        if (empty($formData['newpassword'])) {
            throw new UserException("Es necesario llenar este campo", 422);
        }
    }

    /**
     * Valid repeated new password field
     * @access  private
     * @throws  UserException           If an error occurs during the data base interaction
     * @throws  UserException  If the search is empty or does not satisfy requirements
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validaterepeatnewpassword(array $formData): void
    {
        if (empty($formData['repeatnewpassword'])) {
            throw new UserException("Es necesario llenar este campo", 422);
        }
        if ($formData['newpassword'] !== $formData['repeatnewpassword']) {
            throw new UserException("La constraseña no coincide", 422);
        }
    }
}
