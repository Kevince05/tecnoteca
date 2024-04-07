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
        case "Prenota":
            $id = $_POST['id'];
            $usr = $_SESSION['usr'];
            if (!$db->query("UPDATE oggetti SET prenotabile = 0, fk_id_utente = (SELECT id_utente FROM utenti WHERE username = '$usr') WHERE id_oggetto = $id")) {
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
                        <a class="nav-link disabled" href="reservation.php">Prenota Qui</a>
                    </li>
                    <?php
                    if ($_SESSION['admin']) {
                        echo '<li class="nav-item">
                                <a class="nav-link active" href="admin.php">Amministrazione</a>
                              </li>';
                    }
                    ?>
                </ul>
                <form method="post" action="index.php" class="d-flex">
                    <input type="submit" class="btn btn-danger" name="requestType" value="Logout">
                </form>
            </div>
        </div>
    </nav>
    <div class="container">
        <table class="table table-striped" id="yourObjects">
            <label for="yourObjects" class="form-label">I Tuoi Oggetti</label>
            <thead>
                <tr>
                    <th scope="col">ID Oggetto</th>
                    <th scope="col">Categopria</th>
                    <th scope="col">Centro</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $usr = $_SESSION['usr'];
                $result = $db->query("SELECT * FROM oggetti o LEFT JOIN centri c ON o.fk_id_centro = c.id_centro LEFT JOIN utenti u ON o.fk_id_utente = u.id_utente WHERE u.username = '$usr' ORDER BY o.id_oggetto ASC");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr">
                            <td>' . $row["id_oggetto"] . '</th>
                            <td>' . $row["categoria"] . '</td>
                            <td>' . $row["nome"] . '</td>                            
                          </tr>';
                }
                ?>
            </tbody>
        </table>
        <form class="row mb-3" method="post" id="addForm">
            <label for="addForm" class="form-label">Prenota Oggetto</label>
            <div class="col">
                <select class="form-select" id="centroList" name="centro" required>
                    <option selected value="">All</option>
                    <?php
                    $result = $db->query("SELECT * FROM centri");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option>' . $row["nome"] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col">
                <select class="form-select" id="categoriaList" name="categoria" required>
                    <option selected>All</option>
                    <option>Computer</option>
                    <option>Tablet</option>
                    <option>eBook</option>
                    <option>Viodeogioco</option>
                    <option>Software</option>
                </select>
            </div>
        </form>
        <table class="table table-striped" id="avaiableObjects">
            <label for="avaiableObjects" class="form-label">Oggetti Disponibili</label>
            <thead>
                <tr>
                    <th scope="col">ID Oggetto</th>
                    <th scope="col">Categopria</th>
                    <th scope="col">Centro</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $usr = $_SESSION['usr'];
                $result = $db->query("SELECT * FROM oggetti o LEFT JOIN centri c ON o.fk_id_centro = c.id_centro LEFT JOIN utenti u ON o.fk_id_utente = u.id_utente WHERE o.prenotabile = 1 ORDER BY o.id_oggetto ASC");
                while ($row = $result->fetch_assoc()) {
                    echo '<tr">
                            <td>' . $row["id_oggetto"] . '</th>
                            <td>' . $row["categoria"] . '</td>
                            <td>' . $row["nome"] . '</td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="id" value="' . $row["id_oggetto"] . '">
                                    <input type="submit" class="btn btn-primary" name="requestType" value="Prenota">
                                </form>
                            </td>                            
                          </tr>';
                }
                ?>
            </tbody>
        </table>
        <script>
            table = document.getElementById("avaiableObjects");
            tbody = table.getElementsByTagName("tbody")[0];
            trs = tbody.getElementsByTagName("tr");
            centroList = document.getElementById("centroList");
            categoriaList = document.getElementById("categoriaList");

            function updateTable() {
                for (i = 0; i < trs.length; i++) {
                    tr = trs[i];
                    if ((centroList.selectedIndex != 0 && tr.getElementsByTagName("td")[2].innerText != centroList.options[centroList.selectedIndex].innerText) ||
                        (categoriaList.selectedIndex != 0 && tr.getElementsByTagName("td")[1].innerText != categoriaList.options[categoriaList.selectedIndex].innerText)){
                        tr.style.display = "none";
                    } else {
                        tr.style.display = "";
                    }
                }
            }

            centroList.addEventListener("change", updateTable);
            categoriaList.addEventListener("change", updateTable);
        </script>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>