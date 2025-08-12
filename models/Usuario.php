<?php

namespace Model;

class Usuario extends ActiveRecord
{
    // Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido',  'email', 'password', 'telefono', 'token', 'admin', 'confirmado'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $token;
    public $admin;
    public $confirmado;


    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
    }

    // Mensajes de validación para la creación de una cuenta
    public function validarNuevaCuenta()
    {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre es Obligatorio';
        }
        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es Obligatorio';
        }
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }
        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }


        return self::$alertas;
    }

    // Revisa si el usuario ya existe
    public function existeUsuario()
    {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);


        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El Usuario ya está registrado';
        }

        return $resultado;
    }

    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken()
    {
        $this->token = uniqid();
    }

    // En Model/Usuario.php

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';    
        }
        
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es Obligatorio';    
        }

        return self::$alertas;
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

   public function comprobarPasswordAndVerificado($password) {
    $resultado = password_verify($password, $this->password);
    
    if (!$resultado) {
        self::$alertas['error'][] = 'Password Incorrecto';
        return false;
    }
    
    if (!$this->confirmado) {
        self::$alertas['error'][] = 'Tu cuenta no ha sido confirmada';
        return false;
    }
    
    return true;
}

    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El Password es obligatorio';
        }

        if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }
}
