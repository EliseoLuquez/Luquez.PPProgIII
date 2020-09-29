<?php
use \Firebase\JWT\JWT;
use Monolog\Formatter\WildfireFormatter;
use Monolog\Handler\SwiftMailerHandler;

require_once __DIR__ . '/vendor/autoload.php';

class ValidadorJWT
{

    public static function CrearToken($dato)
    {
        $retorno = false;
        $key = 'primerparcial';
        $payload = array(
            "email" => $dato['email'],
            "tipo" => $dato['tipo_usuario'],
        );
        $retorno = JWT::encode($payload, $key);
        return $retorno;
    }


    public static function VerificarToken($token)
    {
        try
        {
            $retorno = JWT::decode($token, 'primerparcial', array('HS256'));
        }
        catch(Exception $e)
        {
            $retorno = false;
        }
        return $retorno;
    }

}