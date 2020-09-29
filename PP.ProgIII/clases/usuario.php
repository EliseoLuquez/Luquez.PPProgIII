<?php
include_once 'archivos.php';
include_once 'validadorjwt.php';

class Usuario
{
    public $email;
    public $tipo_usuario;
    public $passwowrd;
    public $foto;

    public function __construct($email, $tipo_usuario, $passwowrd, $foto)
    {
        $this->email = $email;
        $this->tipo_usuario = $tipo_usuario;
        $this->passwowrd = $passwowrd;
        $this->foto = $foto;
    }

    public static function Singin($email, $tipo, $passwowrd, $foto)
    {
        $retorno = false;
        $usuario = new Usuario($email, $tipo, $passwowrd, $foto);
        if(Archivos::Imagen($foto))
        {
            if(Archivos::Guardar($usuario, 'usuarios.json'))
            {
                $retorno = true;
            }
        }
        return $retorno;
    }
    
    public static function Login($email, $passwowrd)
    {
        $retorno = false;

        if(Archivos::Leer('usuarios.json', $listaUsuarios))
        {
            foreach($listaUsuarios as $usuario)
            {
                if($usuario['email'] == $email && $usuario['passwowrd'] == $passwowrd)
                {
                    $token = ValidadorJWT::CrearToken($usuario);
                    $retorno = true;
                    break;
                }
            }
        }
        if($retorno)
        {
            $retorno = $token;
        }
        return $retorno;
    }

    // TRUE ya existe el usuario
    public function BuscarUsuario($email)
    {   
        $retorno = false;

        if(Archivo::Leer('users.txt', $listaUsuarios))
        {
            foreach ($listaUsuarios as $auxUr)
            {
                if($email == $auxUr['email'])
                {
                    $retorno = true;
                    break;
                }
            }   
        }
        return $retorno;
    }

    public function EsAdmin($dato)
    {
        $retorno = false;
        $tipo = 'admin';

        if($dato->tipo == $tipo)
        {
            $retorno = true;
        }
        return $retorno;
    }
}