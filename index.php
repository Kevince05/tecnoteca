<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['usr'])) {
    header("Location: login.php");
    exit;
}

// Logout functionality
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tecnoteca Pubblica</title>
    <link rel="stylesheet" type="text/css" href="style/index_style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body <?php
        $bga = ["login_backgrounds/background.png", "login_backgrounds/background1.png", "login_backgrounds/background2.png", "login_backgrounds/background3.png"];
        echo 'style="background-image: url(' . $bga[array_rand($bga, 1)] . '); backgound-repeat:no-repeat; background-size:cover;" data-bs-theme="dark"';
        ?>>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="reservation.php">Prenota Qui</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="avaiability.php">Disponibilità</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="history.php">Cronologia Transazioni</a>
                    </li>
                </ul>   
                <form method="post" action="" class="d-flex">
                    <input type="submit" name="logout" value="Logout">
                </form>
            </div>
        </div>
    </nav>
    <div class="center-text">
        <h1>Tecnoteca Pubblica</h1>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>