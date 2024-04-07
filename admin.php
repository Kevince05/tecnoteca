<?php
session_start();
$error = null;
$db = new mysqli("localhost", "root", "", "tecnoteca");

if ($db->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is not logged in
if (!isset($_SESSION['usr'])) {
    header("Location: login.php");
    exit;
}

//request handle
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    switch ($_POST['requestType']) {
        case "Logout":
            session_destroy();
            header("Location: login.php");
            exit;
        case "Aggiungi":
            $centro = $_POST['centro'];
            $categoria = $_POST['categoria'];
            if (!$db->query("INSERT INTO oggetti (fk_id_centro, categoria) VALUES ($centro, '$categoria')")) {
                $error = "Error:" . $db->error;
            }
            break;
        case "Elimina":
            $id = $_POST['id'];
            if (!$db->query("DELETE FROM oggetti WHERE id_oggetto = $id")) {
                $error = "Error:" . $db->error;
            }
            break;
        case "Edita":
            $id = $_POST['id'];
            $prenotabile = $_POST['prenotabile'];
            if (!$db->query("UPDATE oggetti SET prenotabile = $prenotabile WHERE id_oggetto = $id")) {
                $error = "Error:" . $db->error;
            }
            break;
        case "Ritornato":
            $id = $_POST['id'];
            if (!$db->query("UPDATE oggetti SET fk_id_utente = NULL WHERE id_oggetto = $id")) {
                $error = "Error:" . $db->error;
            }
            break;
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Disponibilità</title>
    <link rel="stylesheet" type="text/css" href="style/reservation_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body <?php
        $bga = ["login_backgrounds/background.png", "login_backgrounds/background1.png", "login_backgrounds/background2.png", "login_backgrounds/background3.png"];
        echo 'style="background-image: url(' . $bga[array_rand($bga, 1)] . '); backgound-repeat:no-repeat; background-size:cover;" data-bs-theme="dark"';
        ?>>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="reservation.php">Prenota Qui</a>
                    </li>
                    <?php
                    if ($_SESSION['admin']) {
                        echo '<li class="nav-item">
                                <a class="nav-link disabled" href="admin.php">Amministrazione</a>
                              </li>';
                    }
                    ?>
                </ul>
                <form method="post" action="index.php" class="d-flex">
                    <input type="submit" class="btn btn-danger" name="requestType" value="Logout">
                </form>
            </div>
    </nav>
    </divc>
    <div class="container">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID Oggetto</th>
                    <th scope="col">Categopria</th>
                    <th scope="col">Centro</th>
                    <th scope="col">Posseduto da</th>
                    <th scope="col">Prenotabile</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $db->query("SELECT * FROM oggetti o LEFT JOIN centri c ON o.fk_id_centro = c.id_centro LEFT JOIN utenti u ON o.fk_id_utente = u.id_utente ORDER BY o.id_oggetto ASC");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr class="' . ($row["prenotabile"] == 0 ? ($row["username"] == NULL ? "table-danger" : "table-primary") : "") . '">
                            <td>' . $row["id_oggetto"] . '</th>
                            <td>' . $row["categoria"] . '</td>
                            <td>' . $row["nome"] . '</td>
                            <td>' . ($row["username"] == NULL ? "NULL" : $row["username"]) . '</td>
                            <form method="post">
                                <td><select class="form-select ' . ($row["username"] == NULL ? "" : "visually-hidden") . '" name="prenotabile">
                                    <option value="1" ' . ($row["prenotabile"] == 1 ? "selected" : "") . '>Si</option>
                                    <option value="0" ' . ($row["prenotabile"] == 0 ? "selected" : "") . '>No</option>   
                                </td>
                                <td>
                                    <input type="hidden" name="id" value="' . $row["id_oggetto"] . '">
                                    <input type="submit" class="btn btn-secondary ' . ($row["username"] == NULL ? "" : "visually-hidden") . '" name="requestType" value="Edita">
                                    <input type="submit" class="btn btn-danger ' . ($row["username"] == NULL ? "" : "visually-hidden") . '" name="requestType" value="Elimina">
                                    <input type="submit" class="btn btn-primary ' . ($row["username"] == NULL ? "visually-hidden" : "") . '" name="requestType" value="Ritornato">
                                </td>
                            </form>
                          </tr>';
                }
                ?>
            </tbody>
        </table>
        <label for="addForm" class="form-label">Aggiungi Oggetto</label>
        <form class="row" method="post" id="addForm">
            <div class="col-5">
                <select class="form-select" id="centroList" name="centro" required>
                    <option selected disabled value="">Seleziona Centro...</option>
                    <?php
                    $result = $db->query("SELECT * FROM centri");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id_centro"] . '">' . $row["nome"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-5">
                <select class="form-select" id="categoriaList" name="categoria" required>
                    <option selected disabled value="">Seleziona Categoria...</option>
                    <option value="Computer">Computer</option>
                    <option value="Tablet">Tablet</option>
                    <option value="eBook">eBook</option>
                    <option value="Viodeogioco">Viodeogioco</option>
                    <option value="Software">Software</option>
                </select>
            </div>
            <div class="col-md-auto">
                <input type="submit" class="btn btn-primary" name="requestType" value="Aggiungi">
            </div>
        </form>
    </div>
    <?php
    if (isset($error)) {
        echo '<div class="error-container"><p>' . $error . '</p></div>';
    }
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>