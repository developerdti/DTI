<?php

declare(strict_types=1);

namespace app\controllers;

use Throwable;
use libs\Controllers, libs\exception\LoginResponseException, libs\exception\MessageException;
use app\models\Session as modelsession;
use app\controllers\Support;

/**
 * Performs validations for Login and Signup by session users
 * @package app\controllers
 * @author Ivan Ezequiel Caballero Cruz
 * @copyright 2023, Cyber team
 */
class Session extends controllers
{

    /**
     * @var     MessageException    $data for messages
     * @access  private
     */
    private MessageException $data;

    /**
     * @var     $session    Results of session user for validations
     * @access  private
     */
    private mixed $session;

    /**
     * @var     string      $ip     ip login user
     * @access  private 
     */
    private string $ip;

    /**
     * Prepares messages compilare and validate if Session exists
     * @access  public
     * @global  array               $_SESSION   Contains user configurations
     * @global  MessageException    $data       Instance message method
     */
    public function __construct()
    {
        $this->data = new MessageException;
        parent::__construct();
    }

    /**
     * Defines the interface
     * @access  public 
     * @throws  DataBaseException If an error occurs during the data base interaction
     * @return  void
     */
    public function index(): void
    {
        if (!empty($_SESSION)) {
            header('Location: Main');
        }
        $this->view->set('filejs', 'session');
        $this->view->render('HeadElements');
        $this->view->render('Session');
        $this->view->render('ScriptElements');
    }

    /**
     * Calls and compyle all of the validations one by one
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If a field does not satisfy the specifications
     * @global  MessageException    $data       Stores error/success message
     * @param   array               $formdata   Form fields
     * @return  void
     */
    private function validateFormFields(array $formData): void
    {
        $errors = false;
        foreach ($formData as $key => $value) {
            try {
                call_user_func_array([$this, 'validate' . $key], [$formData]);
                $this->data->generateStatusMessage('status', $key, ['message' => 'correcto', 'status' => 'valid']);
            } catch (Throwable $e) {
                $errors = true;
                if ($e->getPrevious()) {
                    throw new LoginResponseException($e->getMessage(), $e->getCode(), $e->getPrevious());
                }
                $this->data->generateStatusMessage('status', $key, ['message' => $e->getMessage(), 'status' => 'invalid']);
            }
        }
        if ($errors) {
            throw new LoginResponseException("El formulario contiene errores", 422);
        }
    }

    /**
     * Valid username field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields 
     * @return  void
     */
    private function validateusername(array $formData): void
    {
        if (empty($formData['username'])) {
            throw new LoginResponseException("Es necesario ingresar tus claves", 422);
        }
        $this->session = modelsession::getJobcode($formData['username']);
        if (empty($this->session)) {
            throw new LoginResponseException("Las claves {$formData['username']} no estan registradas", 422);
        }
    }

    /**
     * Valid password field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatepassword(array $formData): void
    {
        if (empty($formData['password'])) {
            throw new LoginResponseException("Es necesario ingresar tu contraseña", 422);
        }
        if (!isset($this->session['password'])) {
            throw new LoginResponseException("La contraseña ingresada no es valida", 422);
        }
        if (!password_verify($formData['password'], $this->session['password'])) {
            modelsession::saveLog($_POST['username'], 0, "Contraseña incorrecta", 0, $this->ip);
            throw new LoginResponseException("La contraseña ingresada es incorrecta", 422);
        }
    }

    /**
     * Performs the sigIn validations and define the session user parameters
     * @access  public
     * @throws  DataBaseException   If an error occurs during the data base interaction
     * @global  MessageException    $data       stores error/success message
     * @global  array               $_SESSION   stores session user configuration
     * @return  void
     */
    public function signIn(): void
    {
        try {
            $this->ip = Support::getIp();
            $this->validateFormFields($_POST);

            $active = modelsession::sessionActive($_POST['username']);
            if(!empty($active)){
                throw new LoginResponseException("Ya hay una sesion iniciada", 428);
            }
            modelsession::saveLog($_POST['username'], 1, 'Accedio correctamente', 1, $this->ip);

            $_SESSION['jobcode'] = $this->session['jobCode'];
            $_SESSION['profileId'] = $this->session['profileId'];
            $_SESSION['group'] = $this->session['clientGroup'];
            $_SESSION['kind'] = (int)$this->session['kind'];
            $_SESSION['name'] = $this->session['firstName'] . ' ' . $this->session['lastName'];
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'SignIn Error',
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
     * Validate fields for the signUp and save the request 
     * @access  public
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If a field does not satisfy the specifications
     * @global  MessageException    $data       stores error/success message
     * @return  void
     */
    public function signUp(): void
    {
        try {
            extract($_POST);

            $valideformfileds = [
                'firstName' => Support::firstletterupper($firstName, 0),
                'secondName' => Support::firstletterupper($secondName, 0),
                'lastName' => Support::firstletterupper($lastName, 1),
                'jobCode' => strtolower($jobCode),
                'signUpPassWord' => $signUpPassWord
            ];
            $this->validateFormFields($valideformfileds);

            $valideformfileds['signUpPassWord'] = $this->encryptPassword($valideformfileds['signUpPassWord']);
            
            modelsession::saveSignUpRequest($valideformfileds);

            $this->data->generateMessage(
                'exito',
                ['title' => 'Registro exitoso', 'message' => 'Espere confirmacion de su encargado']
            );
        } catch (Throwable $e) {
            $previous = $e->getPrevious() ? $e->getPrevious()->getMessage() : '';
            $this->data->generateMessage(
                'warning',
                MessageException::createMessage(
                    'SignUp Error',
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
     * Valid Jobcode field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatejobCode(array $formData): void
    {
        if (empty($formData['jobCode'])) {
            throw new LoginResponseException("Claves obligatorias", 422);
        }
        Support::filterCharacters($formData['jobCode']);
        
        $validejobcode = modelsession::valideRequestJobcode($formData['jobCode']);
        if (!empty($validejobcode)) {
            if ($validejobcode['status'] === '0') {
                throw new LoginResponseException("Estas claves ya fueron solicitadas", 428);
            }
            if ($validejobcode['status'] === '1') {
                throw new LoginResponseException("Estas claves ya fueron registradas", 428);
            }
        }

    }

    /**
     * Valid First Name field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatefirstName(array $formData): void
    {
        if (empty($formData['firstName'])) {
            throw new LoginResponseException("Nombre obligatorio", 422);
        }
        Support::filterCharacters($formData['firstName']);
    }

    /**
     * Valid Second Name field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatesecondName(array $formData): void
    {
        Support::filterCharacters($formData['secondName']);
    }

    /**
     * Valid Last Name field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatelastName(array $formData): void
    {
        if (empty($formData['lastName'])) {
            throw new LoginResponseException("Apellidos obligatorios", 422);
        }
        Support::filterCharacters($formData['lastName']);
    }

    /**
     * Unset and destroy the current user session
     * @access  private
     * @global  MessageException        $data       Stores error/success message
     * @return  void
     */
    public function logOut(): void
    {
        if (empty($_SESSION)) {
            header('location:' . APLICATION_PATH . '/Session');
        }
        
        $active = modelsession::sessionDestroy($_SESSION['jobcode']);
        session_unset();
        session_destroy();
        header('location:' . APLICATION_PATH . '/Session');
    }

    /**
     * Valid signUp password field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validatesignUpPassWord(array $formdata): void
    {
        if (empty($formdata['signUpPassWord'])) {
            throw new LoginResponseException("llenar campo, obligatorio", 422);
        }
    }

    /**
     * Valid group field
     * @access  private
     * @throws  DataBaseException       If an error occurs during the data base interaction
     * @throws  LoginResponseException  If the search is empty or does not satisfy requirements
     * @global  MessageException        $data       Stores error/success message
     * @param   array                   $formdata   Form fields
     * @return  void
     */
    private function validategroup(array $formdata)
    {
        if ($formdata['group'] === 'Group') {
            throw new LoginResponseException("Seleccionar un grupo", 422);
        }
    }

    /**
     * Encrypt password
     * @access  private
     * @param   string  $password   password to encrypt
     * @return  string
     */
    public function encryptPassword($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }
}
