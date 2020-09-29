<?php

class Asignacion
{
    public $legajo;
    public $id;
    public $turno;

    function __construct($legajo, $id, $turno)
    {
        $this->legajo= $legajo;
        $this->id = $id;
        $this->turno = $turno;
    }

    static function ExisteLegajoEnTurno($legajo, $turno)
    {
        $retorno = false;
        $leer = Archivos::Leer('materias-profesores.json', $asignaciones);
        
        if($leer)
        {
            foreach ($asignaciones as $key) 
            {
                if($key['turno'] == $turno && $key['legajo'] == $legajo)
                {
                    $retorno = true;
                }
            }
        }
        return $retorno;
    }

    static function Listar()
    {
        if(Archivos::Leer('materias-profesores.json' ,$matprof))
        {
            return $matprof;
        }
    }
}