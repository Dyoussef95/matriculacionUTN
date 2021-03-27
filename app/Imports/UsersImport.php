<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {

        //Cargo un array con los usuarios
        $usuarios = array();
        foreach ($rows as $row) {
            $usuarios[] = $row[2];
        }
        $usuarios = array_unique($usuarios); //array_unique() elimina duplicados
        // dd(count($usuarios));

        //Cargo un array con las materias
        $materias = array();
        foreach ($rows as $row) {
            $materias[] = trim($row[7]); //trim() elimina espacios en blanco
        }
        $materias = array_unique($materias);
        // dd(count($materias));


        //Empiezo a armar la tabla
        $s = '<table border="1">
        <tr>
          <th>DNI</th>';
        $i = 1;
        //Armo el encabezado de la tabla
        foreach ($materias as $materia) {
            $s .= '<th>course' . $i . '</th>';
            $s .= '<th>group' . $i . '</th>';
            $i = $i + 1;
        }
        $s .= '</tr>';

        $i = 0;

        //Recorro el array de usuarios
        foreach ($usuarios as $usuario) {
            if ($i != 0) {

                $s .= '<tr>';

                $s .= '<td>' . $usuario . '</td>';

                //Cargo un array que contenga las materias del usuario actual
                $materiasUsuario = array();
                $comisionesUsuario = array();
                foreach ($rows as $row) {
                    if (strcmp($row[2], $usuario) === 0) {
                        $materiasUsuario[trim($row[8])] = trim($row[7]);
                        $comisionesUsuario[trim($row[8])] = trim($row[9]);
                    }
                }
               

                //Recorro el array de materias
                $j = 0;
                foreach ($materias as $materia) {
                    if ($j != 0) {
                        //Recorro el array de materias del usuario actual
                        $b = 0;
                        foreach ($materiasUsuario as $corto => $materiaUsuario) {
                            //Utilizo una bandera para detectar si el usuario contiene la materia actual
                           
                            if(strcmp($materia,$materiaUsuario) === 0){
                                $b=1;
                                $nombreCorto = $corto;
                                $grupo = $comisionesUsuario[$corto];
                            }
                        }
                        if ($b == 1) {
                            $s .= '<td>'.$nombreCorto.'</td>';
                            $s .= '<td>'.$grupo.'-2021</td>';
                        } else {
                            $s .= '<td></td>';
                            $s .= '<td></td>';
                        }
                        
                    }
                    $j = $j + 1;
                }

                $s .= '</tr>';
            }
            $i = $i + 1;
        }
        $s .= '</table>';
        echo $s;
        dd("hola");
    }
}
