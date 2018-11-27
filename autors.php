<?php
session_start();
$autors = [];
$paginacio = [];
if (!isset($_POST)) {
    session_destroy();
}
if (isset($_SESSION["paginacio"])) {
    $paginacio = $_SESSION["paginacio"];
} else {
    $paginacio = [
            "actualPage" => 1,
            "nextPage" => 1,
            "prevPage" => 1,
            "totalPages" => 1,
            "resultsPerPage" => 10,
            "totalResults" => 0,
            "from" => 0,
    ];
    $_SESSION["paginacio"] = $paginacio;

}

if (isset($_POST["next"])) {
    $paginacio["actualPage"]++;
}
if (isset($_POST["prev"])) {
    $paginacio["actualPage"]--;
}
if (isset($_POST["first"])) {
    $paginacio["actualPage"] = 1;
}
if (isset($_POST["last"])) {
    $paginacio["actualPage"] = $paginacio['totalPages'];
}

$ordre = "nomasc";
$queryAutors = "SELECT ID_AUT, NOM_AUT FROM AUTORS";
// ORDENAR DES DE QUERY
include("conn.php");

function calculatePagination($result, $paginacio)
{
    $paginacio["totalResults"] = count($result);
    $paginacio["totalPages"] = $paginacio["totalResults"] / $paginacio["resultsPerPage"];
    $paginacio["from"] = ($paginacio["actualPage"] - 1) * $paginacio["resultsPerPage"];
    return $paginacio;
}

// function nextPage($paginacio)
// {
//     return $paginacio;
// }

if (isset($_POST["cerca"])) {
    $cerca = $_POST["cerca"];

    $queryAutors = $queryAutors . " WHERE ID_AUT = '" . $cerca . "' OR NOM_AUT LIKE '%" . $cerca . "%'";
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


$result = $mysqli->query($queryAutors);
$autors = $result->fetch_all(MYSQLI_ASSOC);

$paginacio = calculatePagination($autors, $paginacio);
$_SESSION['paginacio'] = $paginacio;
$queryAutors = $queryAutors . " LIMIT " . $paginacio["from"] . ", " . $paginacio["resultsPerPage"];

if ($result = $mysqli->query($queryAutors)) {
    $autors = $result->fetch_all(MYSQLI_ASSOC);
        
    $result->free();
}
$mysqli->close();


foreach ($_SESSION['paginacio'] as $valor) {
    echo $valor .  " ";
}
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

    <div class="container">
        
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
            <input type="text" class="form-control" id="cerca" name="cerca" placeholder="Cerca..." <?php if (isset($cerca)) {
                                                                                                        echo 'value="' . $cerca . '"';
                                                                                                    } ?>>
        </div>
        <div class="form-group mx-sm-3">
            <button type="submit" name="ordenar" class="btn btn-primary mb-2">Filtrar</button>
        </div>
        <div class="form-group mx-sm-3">            
            <button type="submit" name="first" class="btn btn-primary mb-2">First</button>
            <button type="submit" name="prev" class="btn btn-primary mb-2">Prev</button>
            <button type="submit" name="next" class="btn btn-primary mb-2">Next</button>
            <button type="submit" name="last" class="btn btn-primary mb-2">Last</button>
        </div>
    </form>
    Query: <?php echo $queryAutors ?>
    <br>
    Pàgina <?= $paginacio["actualPage"] ?> de <?= $paginacio["totalPages"] ?>
    
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

</div>
</body>
</html>