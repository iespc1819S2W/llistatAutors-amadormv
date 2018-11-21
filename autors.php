<?php
$autors = [];
$ordre = "nomasc";
$queryAutors = "SELECT ID_AUT, NOM_AUT FROM AUTORS";
// ORDENAR DES DE QUERY
include("conn.php");


if ($_POST["cerca"]) {
    $cerca = $_POST["cerca"];

    $queryAutors = $queryAutors . " WHERE ID_AUT = '" . $cerca . "' OR NOM_AUT LIKE '" . $cerca . "'";
}
if (isset($_POST["ordenar"])) {
    $ordre = $_POST["ordre"];
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <!-- Image and text -->
    <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">
        <img src="logoies.png" width="30" height="30" class="d-inline-block align-top" alt="">
        IES Pau Casesnoves
    </a>
    </nav>


    <h1>Llistat autors</h1>
    <form class="form-inline" action="" method="post">
        <div class="form-group mb-2">
            <label for="ordre" class="sr-only">Ordenar per</label>
            <select name="ordre" id="ordre" class="form-control">
                <option <?php if ($ordre == "nomasc") {
                            echo "selected ";
                        } ?> value="nomasc">Nom - Ascendent</option>
                <option <?php if ($ordre == "nomdsc") {
                            echo "selected ";
                        } ?> value="nomdsc">Nom - Descendent</option>
                <option <?php if ($ordre == "idasc") {
                            echo "selected ";
                        } ?> value="idasc">ID - Ascendent</option>
                <option <?php if ($ordre == "iddsc") {
                            echo "selected ";
                        } ?> value="iddsc">ID - Descendent</option>
            </select>
        </div>
        <div class="form-group mx-sm-3 mb-2">
            <input type="text" class="form-control" id="cerca" name="cerca" placeholder="Cerca...">
        </div>
        <button type="submit" name="ordenar" class="btn btn-primary mb-2">Filtrar</button>
    </form>
    Query: <?php echo $queryAutors ?>
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"># ID</th>
                <th scope="col">Nom Autor</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($autors as $autor) {
                echo "<tr>";
                    echo '<th scope="row">' . $autor["ID_AUT"] . '</th>';
                    echo '<th scope="row">' . $autor["NOM_AUT"] . '</th>';
                echo "</tr>";
            }

            ?>
        </tbody>
    </table>
</body>
</html>