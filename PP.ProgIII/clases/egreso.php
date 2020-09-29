<?php
include_once 'archivos.php';

class Egreso
{
    public $patente;
    public $fecha_egreso;
    public $importe;

    function __construct($patente, $fecha_egreso, $importe)
    {
        $this->patente = $patente;
        $this->fecha_egreso = $fecha_egreso;
        $this->importe = $importe;
    }

    static function CalcularImporte($fecha_egreso)
    {
        if(Archivos::Leer('autos.json', $ingresos))
        {
            foreach ($ingresos as $key) 
            {
                $horaIngreso = $key->fecha_ingreso->hour;
                $horaEgreso = $fecha_egreso->hour;
                $estadia = $horaEgreso - $horaIngreso;
                if($estadia < 4)
                {
                    $importe = $estadia * 100;
                }
                else if($estadia >= 4 && $estadia <= 12)
                {
                    $importe = $estadia * 60;
                }
                else
                {
                    $importe = $estadia * 30;
                }
            }
            return $importe;
        }


    }

    static function ExisteLegajo($legajo)
    {
        $retorno = false;
        $leer = Archivos::Leer('Egresoes.json', $Egresoes);

        if($leer)
        {
            foreach ($Egresoes as $key) 
            {
                if($legajo == $key['legajo'])
                {
                    $retorno = true;
                }
            }
        }

        return $retorno;
    }

    static function Listar()
    {
        if(Archivos::Leer('Egresoes.json' ,$Egresoes))
        {
            return $Egresoes;
        }
    }
}