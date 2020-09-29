<?php

class Ingreso
{
    public $patente;
    public $email;
    public $fecha_ingreso;

    public function __construct($patente, $email, $fecha_ingreso)
    {
        $this->patente = $patente;
        $this->email = $email;
        $this->fecha_ingreso = $fecha_ingreso;
    }

    static function TraerMateria($patente)
    {
        $retorno = '';

        if(Archivos::Leer('materias.json', $materias))
        {
            foreach ($materias as $materia) 
            {
                if($materia['patente'] == $patente)
                {
                    $retorno = $materia['email'];
                }
            }
        }
        return $retorno;
    }

    static function Listar()
    {
        if(Archivos::Leer('autos.json' ,$ingresos))
        {
            //return sort($ingreso, SORT_STRING);
            return $ingresos;
        }
    }

}