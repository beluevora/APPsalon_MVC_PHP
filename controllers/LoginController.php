<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
    public static function login(Router $router){
        $alertas = [];
        $auth = new Usuario;

        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                //Comprobar que exista el usuario
                $usuario = Usuario::where ('email', $auth->email);

                if($usuario){
                    //verificar contraseña
                   if( $usuario->comprobarPasswordAndVerificado($auth->password)) {
                    //Si todo está bien hay que autenticar al usuario:
                    if(!isset($_SESSION)) {
                        session_start();
                    }
                    

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;
                    //Redireccionar al usuario:
                    if($usuario->admin === "1"){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                        header('Location: /admin');
                    } else {header('Location: /cita');
                    }
                   };
                } else {
                    Usuario::setAlerta('error', 'El usuario no tiene una cuenta registrada');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas, 
            'auth' => $auth,
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function forgot(Router $router){
        $alertas=[];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();
            if(empty($alertas)){
                //verificar que el email forme parte de la base de datos:
                $usuario = Usuario::where('email', $auth->email);
                if($usuario && $usuario->confirmado="1"){
                    //Acá el usuario verificó que existe pero que se olvidó la contraseña, hay que generar token:
                        $usuario->crearToken();
                        $usuario->guardar();

                        //Enviar el mail: 
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarInstrucciones();
                        Usuario::setAlerta('exito', 'Email de recuperación de contraseña enviado correctamente');
                        
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado.');
                    
                }
            }
        }
        $alertas= Usuario::getAlertas();

       $router->render('auth/forgot', [
        'alertas'=>$alertas,
       ]);
    }

    public static function recuperar(Router $router){
        $alertas=[];
        $error = false;


        $token = s($_GET['token']);
        //buscar usuario por su token:

        $usuario= Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
            $error= true;
        }  

        if($_SERVER['REQUEST_METHOD']==='POST'){
            //lEER LA NUEVA CONTRASEÑA Y GUARDARLA:
            $password = new Usuario($_POST);
            $alertas = $password->validarContraseña();

            if(empty($alertas)){
                $usuario->password = null;

                $usuario->password = $password->password;
                $usuario->hashContraseña();

                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }

            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-contraseña', [
            'alertas'=>$alertas,
            'error'=>$error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;

        //Alertas vacías
        $alertas = [];
       if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alertas esté vacío
            if(empty($alertas)) {
                //Una vez que se ve que no hay errores hay que verificar que el usuario ya no tenga una cuenta ahí:
                $resultado = $usuario->existeUsuario();      
                
                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                } else {
                    //Hashear la contraseña:
                    $usuario->hashContraseña();

                    //Generar token de seguridad:
                    $usuario->crearToken();

                    //Enviar el mail de confirmación
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //Crear el usuario:
                    $resultado = $usuario->guardar();
                    if($resultado) {
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

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token= s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            //si el usuario está vacío mostrar mensaje de error:
            Usuario::setAlerta('error', 'Token no válido');
        } else {
            //si no está vacío  y el token es válido se confirma el usuario:
            $usuario->confirmado = "1";
            $usuario->token = '';
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Tu cuenta ha sido confirmada correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
    
}