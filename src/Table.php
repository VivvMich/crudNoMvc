<?php

namespace App\Crud;

class Table
{
    public static function drawTable(array $objects){ // Tableau d'objets pour ce cas un tableau de User
        $table = '';
        $head = '';
        $rows = '';

        $ref = new \ReflectionClass($objects[0]);// pour avoir les propriétés par exemple de User, je prends le premier du tableau d'objet
        $properties = $ref->getProperties();

        foreach ($properties as $property) {
            $prop = ucfirst($property->getName());
            $head .= "<th>$prop</th>";
        }

        foreach ($objects as $object){
            $rows.= "<tr>";
            foreach ($properties as $property){
                $method = 'get' . ucfirst($property->getName());
                if (method_exists($object, $method)) {
                    $result = $object->$method();
                    $rows.= "<td>$result</td>";
                }
            }
            $rows .= "<tr>";
        }
        $table = "<div class='container'><table class='table'><thead><tr>$head</tr></thead><tbody>$rows</tbody></table</div>";

        echo $table;
    }
}