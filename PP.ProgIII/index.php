<?php

include_once './clases/usuario.php';
include_once './clases/ingreso.php';
include_once './clases/egreso.php';
include_once './clases/archivos.php';
include_once 'validadorjwt.php';

session_start();

$request_method = $_SERVER['REQUEST_METHOD'];
$path_info = $_SERVER['PATH_INFO'];

//var_dump($patente);
//$fecha = getdate();
$fecha = date("Y-m-d H:i:s"); 
//var_dump(date_parse($fecha));
$header = getallheaders();
//var_dump($header);
switch($request_method)
{
    case 'POST':
        switch($path_info)
        {
            case '/registro'://Punto 1
                if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['tipo']) && isset($_FILES['imagen']))
                {
                    if(Usuario::Singin($_POST['email'], $_POST['tipo'], $_POST['password'], $_FILES['imagen']))
                    {
                        $datos = 'Singin Exitoso';
                    }
                    else
                    {
                        $datos = 'Singin Error';
                    }
                }
                else
                {
                    $datos = 'Faltan datos';
                    
                }
            break;
            case '/login'://PUNTO 2
                if (isset($_POST['email']) && isset($_POST['password']))
                {
                    $datos = Usuario::Login($_POST['email'], $_POST['password']);
                    if($datos == '')
                    {
                        $datos = 'Login Error';
                    }
                }
                else
                {
                    $datos = 'Faltan datos';
                    
                }
            break;
            case '/ingreso'://PUNTO 3
                $token = $header['token'];

                if (isset($_POST['patente']))
                {
                    $existeToken = ValidadorJWT::VerificarToken($token);
                    if($existeToken)
                    {
                        $tipoUsuario = Usuario::EsAdmin($existeToken);
                        if(!$tipoUsuario)
                        {
                            $ingreso = new Ingreso($_POST['patente'], $existeToken->email, $fecha);
                            if(Archivos::Guardar($ingreso, 'autos.json'))
                            {
                                $datos = 'Ingreso Exitoso';
                            }
                        }
                        else
                        {
                            $datos = 'Debe ser tipo user';
                        }
                    }
                    else
                    {
                        $datos = 'Token Inexistente';
                    }
                }
                else
                {
                    $datos = 'Faltan datos';
                }
            break;
            case '/retiro'://PUNTO 5
                $token = $header['token'];
                if (isset($_POST['legajo']) && isset($_POST['id']) && isset($_POST['turno']))
                {
                    if(ValidadorJWT::VerificarToken($token))
                    {
                        if(Profesor::ExisteLegajo(($_POST['legajo'])))
                        {
                            if(!Asignacion::ExisteLegajoEnTurno($_POST['legajo'], $_POST['turno']))
                            {
                                $asignacion = new Asignacion($_POST['legajo'], $_POST['id'], $_POST['turno']);
        
                                if(Archivos::Guardar($asignacion, 'materias-profesores.json'))
                                {
                                    $datos = 'Asignacion Exitosa';
                                }
                            }
                            else
                            {
                                $datos = 'Legajo ya asignado a materia-turno';
                            }
                        }
                        else
                        {
                            $datos = 'Legajo Inexistente';
                        }
                    }
                    else
                    {
                        $datos = 'Token Inexistente';
                    }
                }
                else
                {
                    $datos = 'Faltan datos';
                }
            break;
            case '/usuario/{email}':
            break;
            default:
            break;
        }
    break;
    case 'GET':
        $token = $header['token'];
        switch($path_info)
        {   
            case 'retiro'://PUNTO 4
                $datos = 'entreo a case';
                $path = explode('/', getenv('REQUEST_URI'));
                $patente = $path[5];
                if(ValidadorJWT::VerificarToken($token))
                {
                    $importe = Egreso::CalcularImporte($fecha);
                    $egreso = new Egreso($patente, $fecha, $importe);

                    $datos = $importe;
                }
                else
                {
                    $datos = 'Token Inexistente';
                }
            break;
            case '/ingreso'://PUNTO 5
                if(ValidadorJWT::VerificarToken($token))
                {
                    $datos = Ingreso::Listar();
                }
                else
                {
                    $datos = 'Token Inexistente';
                }
            break;
            case 'ingreso'://PUNTO 6
                if(ValidadorJWT::VerificarToken($token))
                {
                    $datos = Ingreso::Listar();
                }
                else
                {
                    $datos = 'Token Inexistente';
                }
            break;
            default:
            break;
        }
    break;
    
    default:
    break;
}

$respuesta = new stdClass;
$respuesta->success  = true;
$respuesta->data = $datos;

echo json_encode($respuesta);
