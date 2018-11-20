<?php
$autors = [];
$ordre = "nomasc";
$queryAutors = "SELECT ID_AUT, NOM_AUT FROM AUTORS";
// ORDENAR DES DE QUERY
include("conn.php");


if (isset($_POST["ordenar"])) {
    $ordre = $_POST["ordre"];
    echo "ORDENAT";
    switch ($ordre) {
        case 'nomasc':
            $queryAutors = $queryAutors . " ORDER BY NOM_AUT ASC";
            break;
        case 'nomdsc':
            $queryAutors = $queryAutors . " ORDER BY NOM_AUT DESC";
            break;
        case 'idasc':
            $queryAutors = $queryAutors . " ORDER BY ID_AUT ASC";
            break;
        case 'iddsc':
            $queryAutors = $queryAutors . " ORDER BY ID_AUT DESC";
            break;
        default:
            # code...
            break;
    }
}


if ($result = $mysqli->query($queryAutors)) {
    $autors = $result->fetch_all(MYSQLI_ASSOC);
        // while ($row = $result->fetch_assoc()) {
        //     // echo $row["ID_AUT"].'-'. $row["NOM_AUT"];
        //     $autors[] = $row;
        // }
    $result->free();
}
$mysqli->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Autors</title>
</head>
<body>
    <form action="" method="post">
        <select name="ordre" id="ordre">
            <option <?php if($ordre == "nomasc") { echo "selected "; } ?> value="nomasc">Nom - Ascendent</option>
            <option <?php if($ordre == "nomdsc") { echo "selected "; } ?> value="nomdsc">Nom - Descendent</option>
            <option <?php if($ordre == "idasc") { echo "selected "; } ?> value="idasc">ID - Ascendent</option>
            <option <?php if($ordre == "iddsc") { echo "selected "; } ?> value="iddsc">ID - Descendent</option>
        </select>
        <button type="submit" name="ordenar">Ordenar</button>
    </form>
    Query: <?php echo $queryAutors ?>
    <pre> <?php print_r($autors); ?> </pre>
    <table>
        <tr>
            <th>ID Autor</th>
            <th>Nom Autor</th>
        </tr>
        <?php
            foreach ($autors as $autor) {
                # code...
            }
            echo "<td>";
            echo "</td>";
        ?>
    </table>
</body>
</html>