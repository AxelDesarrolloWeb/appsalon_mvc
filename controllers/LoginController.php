<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class LoginController
{
    // En LoginController.php

    public static function login(Router $router)
    {
        $auth = new usuario;
        $alertas = []; // Inicializar alertas


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin(); // Nuevo método que crearemos

            // Si no hay errores, autenticar
            if (empty($alertas)) {

                // Comprobar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Verificar el password
                    if ($usuario->comprobarPasswordAndVerificado($auth->password)) {
                        // Autenticar al usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;


                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                }
            } else {
                Usuario::setAlerta('error', 'Usuario no encontrado');
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas, // Pasar alertas a la vista
        ]);
    }


    public static function logout()
    {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

   public static function olvide(Router $router)
{
    $alertas = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $auth = new Usuario($_POST);
        $alertas = $auth->validarEmail();

        if (empty($alertas)) {
            // Buscar usuario por email
            $usuario = Usuario::where('email', $auth->email);
            
            if ($usuario && $usuario->confirmado === "1") {
                // Generar y guardar token
                $usuario->crearToken();
                $usuario->guardar(); // Guardar el token en la base de datos

                // Enviar email
                $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                $email->enviarInstrucciones();
                
                Usuario::setAlerta('exito', 'Revisa tu email');
            } else {
                Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
            }
        }
    }
    
    $alertas = Usuario::getAlertas();
    
    $router->render('auth/olvide-password', [
        'alertas' => $alertas
    ]);
}

    public static function recuperar(Router $router)
{
    $alertas = [];
    $token = s($_GET['token']);
    $error = false;

    // Buscar usuario por su token
    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
        Usuario::setAlerta('error', 'Token No válido');
        $error = true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Leer el nuevo password y guardarlo
        $password = new Usuario($_POST);
        $alertas = $password->validarPassword();

        if (empty($alertas)) {
            // Eliminar password anterior
            $usuario->password = null;
            
            // Asignar nuevo password
            $usuario->password = $password->password;
            
            // Hashear password
            $usuario->hashPassword();
            
            // Eliminar token
            $usuario->token = null;

            // CORRECCIÓN: Guardar el usuario correctamente
            $resultado = $usuario->guardar();

            if ($resultado) {
                // Redireccionar al login
                header('Location: /');
                return; // Importante: salir después de redireccionar
            }
        }
    }

    $alertas = Usuario::getAlertas();
    $router->render('auth/recuperar-password', [
        'alertas' => $alertas,
        'error' => $error
    ]);
}

    public static function crear(Router $router)
    {
        $usuario = new Usuario;
        // Alertas vacías
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //   session_start();

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta esté vacío
            if (empty($alertas)) {
                // Verificar que el usuario no esté verificado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear un usuario
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            // Modificar a usuario confirmado

            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        // Mostrar alertas
        $alertas = Usuario::getAlertas();

        // Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
