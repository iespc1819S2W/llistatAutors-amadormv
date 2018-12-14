<?php
session_start();
require('funcions.php');
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
    if ($paginacio["actualPage"] < $paginacio["totalPages"]) {
        $paginacio["actualPage"]++;
    }
}
if (isset($_POST["prev"])) {
    if ($paginacio["actualPage"] > 1) {
        $paginacio["actualPage"]--;
    }
}
if (isset($_POST["first"])) {
    $paginacio["actualPage"] = 1;
}
if (isset($_POST["last"])) {
    $paginacio["actualPage"] = $paginacio['totalPages'];
}

$ordre = "nomasc";
$queryAutors = "SELECT ID_AUT, NOM_AUT, FK_NACIONALITAT FROM AUTORS";
// ORDENAR DES DE QUERY
include("conn.php");

if (isset($_POST["afegir"])) {
    $nouAutor = $_POST["nomAutor"];
    $nacionalitatAutor = $_POST["nacionalitat"];

    $row = $mysqli->query("SELECT MAX(ID_AUT) FROM AUTORS");
    $resultId = $row->fetch_row();
    $id = $resultId[0];
    $id++;
    $sql = "INSERT INTO AUTORS(ID_AUT, NOM_AUT, FK_NACIONALITAT) VALUES ($id, '$nouAutor', '$nacionalitatAutor')";
    $resultInsert = $mysqli->query($sql);

}

if (isset($_POST["guardar"])) {
    $id = $_POST["idAutor"];
    $nomAutor = $_POST["nomAutor"];
    $nacionalitatAutor = $_POST["nacionalitat"];
    $sql = "UPDATE AUTORS SET NOM_AUT = '$nomAutor', FK_NACIONALITAT = '$nacionalitatAutor' WHERE ID_AUT = $id";
    $resultUpdate = $mysqli->query($sql);
}

if (isset($_POST["borrar"])) {
    $id = $_POST["idAutor"];
    $sql = "DELETE FROM AUTORS WHERE ID_AUT = $id";
    $resultDelete = $mysqli->query($sql);
}

$idAutorEditar = 0;
if (isset($_POST["editar"])) {
    $idAutorEditar = $_POST["idAutor"];
}


function calculatePagination($result, $paginacio)
{
    $paginacio["totalResults"] = count($result);
    $paginacio["totalPages"] = $paginacio["totalResults"] / $paginacio["resultsPerPage"];
    $paginacio["from"] = ($paginacio["actualPage"] - 1) * $paginacio["resultsPerPage"];
    return $paginacio;
}


if (isset($_POST["cerca"])) {
    $cerca = $_POST["cerca"];

    $_SESSION["cerca"] = $cerca;

    $queryAutors = $queryAutors . " WHERE ID_AUT = '" . $cerca . "' OR NOM_AUT LIKE '%" . $cerca . "%'";

} else if (isset($_SESSION["cerca"])) {

    $cerca = $_SESSION["cerca"];

    $queryAutors = $queryAutors . " WHERE ID_AUT = '" . $cerca . "' OR NOM_AUT LIKE '%" . $cerca . "%'";
}
if (isset($_POST["ordenar"]) || isset($_SESSION["ordre"])) {
    // $_POST["ordre"] ? $ordre = $_POST["ordre"] : $ordre = "";
    if (isset($_SESSION["ordre"])) {
        $ordre = $_SESSION["ordre"];
    } else {
        $_SESSION["ordre"] = $_POST["ordre"];
        $ordre = $_POST["ordre"];
    }
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Autors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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
        
        <h1>Llistat autors
        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#afegirModal">
            Nou
        </button>
        </h1>
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
    <br>
    Pàgina <?= $paginacio["actualPage"] ?> de <?= $paginacio["totalPages"] ?>
    
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"># ID</th>
                <th scope="col">Nom Autor</th>
                <th scope="col">Nacionalitat</th>
                <th scope="col">Acció</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($autors as $autor) {
                echo "<tr>";
                    echo '<form action="" method="post">';
                    echo '<th scope="row"><input type="number" readonly class="form-control-plaintext" name="idAutor" value='.$autor["ID_AUT"].'></th>';
                    echo '<th scope="row"><input type="text" '.  ($idAutorEditar == $autor["ID_AUT"] ? ' class="form-control"' : 'readonly class="form-control-plaintext"')  . ' name="nomAutor" value="'.$autor["NOM_AUT"].'"></th>';
                    echo '<th scope="row">';
                    if ($idAutorEditar == $autor["ID_AUT"]) {
                        montarSelect($mysqli, "SELECT nacionalitat FROM nacionalitats", "nacionalitat", "nacionalitat", "nacionalitat", $autor["FK_NACIONALITAT"]);
                    } else {
                        echo $autor["FK_NACIONALITAT"];
                    }
                    echo '</th>' ;
                    ?>
                    <th scope= "row">
                        <?php 
                        if ($idAutorEditar == $autor["ID_AUT"]) {
                            echo '<button type="submit" name="guardar" id="guardar" class="btn btn-success">Desa</button>';
                        } else {
                            echo '<button type="submit" name="editar" id="editar" class="btn btn-warning">Editar</button>';
                        }
                        ?>
                        <button type="submit" name="borrar" class="btn btn-danger">Borrar</button>
                    </th>
                </form>
                </tr>
                <?php
            }

            ?>
        </tbody>
    </table>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="afegirModal" tabindex="-1" role="dialog" aria-labelledby="afegirModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="afegirModalLabel">Nou autor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nomAutor" class="col-form-label">Nom:</label>
                            <input type="text" class="form-control" id="nomAutor" name="nomAutor" placeholder="LLINATGES, NOM" required>
                        </div>
                        <div class="form-group">
                        <?php 
                        montarSelect($mysqli, "SELECT nacionalitat FROM nacionalitats", "nacionalitat", "nacionalitat", "nacionalitat") 
                        ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tancar</button>
                        <button type="submit" name="afegir" class="btn btn-primary">Afegir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html

<?php

$mysqli->close();


?>