<?php
session_start();
$error = null;
$db = new mysqli("localhost", "root", "", "enoteca");

if ($db->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usr = $_POST["usr"];
    $pwd = md5($_POST["pwd"]);

    if ($_POST["submit_type"] === "Login") {
        $prep = $db->prepare("SELECT username, password FROM utenti WHERE username=? AND password=?");
        $prep->bind_param("ss", $usr, $pwd);
        $prep->execute();
        $result = $prep->get_result();
        if ($result->num_rows == 1) {
            $_SESSION["usr"] = $usr;
            $_SESSION["md5_pwd"] = $pwd;
            header("Location:index.php");
        } else {
            $error = "Wrong user or password";
        }
    } else {
        $prep = $db->prepare("INSERT INTO utenti (username, password) VALUE (?,?)");
        $prep->bind_param("ss", $usr, $pwd);
        if ($prep->execute()) {     
            $_SESSION["usr"] = $usr;
            $_SESSION["md5_pwd"] = $pwd;
            header("Location:index.php");
        } else {
            $error = "Error:" . $db->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="style/login_style.css">
    <title>Login</title>
</head>

<body <?php 
        $bga = ["login_backgrounds/background.png","login_backgrounds/background1.png","login_backgrounds/background2.png","login_backgrounds/background3.png"];
        echo 'style="background-image: url(' . $bga[array_rand($bga,1)] . '); background-size: stretch; background-repeat: no-repeat; background-attachment: fixed; background-position: center;"';
       ?>>
    <div style="display: flex; flex-direction: column">
        <div class="container">
            <form action="login.php" method="POST">
                Username: <input type="text" name="usr" required><br>
                Password: <input type="password" name="pwd" required><br>
                <input type="submit" name="submit_type" value="Login">
                <input type="submit" name="submit_type" value="Register">
            </form>
        </div>
        <?php
        if (isset($error)) {
            echo '<div class="error-container"><p>' . $error . '</p></div>';
        }
        ?>
    </div>
</body>

</html>