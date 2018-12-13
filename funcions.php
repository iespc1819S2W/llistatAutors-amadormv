<?php
    function montarSelect($mysqli,$sql,$valor,$descripcio,$name,$selected){
        
        echo("<select name='$name' id='$name'>");
        echo("<option value=' '> </option>");
        $result = $mysqli->query($sql) or die($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($selected == $row["$descripcio"]) {
                    echo('<option value="'.$row["$valor"].'" ' . $row["$descripcio"].' selected>'.$row["$descripcio"].'</option>');
                } else {
                    echo('<option value="'.$row["$valor"].'" ' . $row["$descripcio"].'>'.$row["$descripcio"].'</option>');
                }
                
            }
            $result->free();
        }
        echo("</select>");
        
    }
?>