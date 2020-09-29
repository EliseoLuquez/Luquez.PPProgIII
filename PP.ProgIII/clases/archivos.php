<?php

class Archivos
{

    static function Leer($path, &$array)
    {
        $retorno = false;
        if(file_exists($path) && filesize($path) > 0)
        {
            $archivo = fopen($path, 'r');
            $array = fread($archivo, filesize($path));
            $cerrar = fclose($archivo);
            $array = json_decode($array, true);
            $retorno = true;
        }
        else
        {
            $array = array();
        }
        return $retorno;

    }

    static function Guardar($dato, $path)
    {
        $retorno = false;
        if(Archivos::Leer($path, $array))
        {
            array_push($array, $dato);
            $aux = json_encode($array, true);
        }
        else
        {
            array_push($array, $dato);
            $aux = json_encode($array, true);
        }
        $archivo = fopen($path, 'w');
        if(fwrite($archivo, $aux))
        {
            $retorno = true;
        }
        $cerrar = fclose($archivo);

        return $retorno;
    }


    static function Reescribir($dato, $path)
    {
        $retorno = false;
        $archivo = fopen($path, 'w');
        if(fwrite($archivo, json_encode($dato, true)))
        {
            $retorno = true;
        }
        $cerrar = fclose($archivo);
        return $retorno;
    }

    static function Imagen($img)
    {
        $retorno = false;

        $extension = explode('.', $img["name"]);
        $extension[0] = rand(100, 10000);

        if(Archivos::ValidarExtension($extension[1]))
        {
            $origen =$img['tmp_name'];
            $destino = 'img/' . $extension[0] . '.' . $extension[1];
            
            if(move_uploaded_file($origen, $destino))
            {
                $retorno = true;
            }
        }
        return $retorno;
    }

    static function ValidarExtension($img)
    {
        $retorno = false;
        
        $array = array('png', 'jpg', 'jpeg', 'gif', 'png', 'tiff', 'tif', 'RAW', 'bmp', 'psd', 'pdf', 'eps', 'pic');

        foreach ($array as $aux)
        {
            if($img == $aux)
            {
                $retorno = true;
                break;
            }
        }
        return $retorno;
    }
}
