<?php

require_once "conexionPDO.php";
$pdo = conexionPDO();

session_start();

if (isset($_SESSION['user'])) {
    header('Location: ToDoList.php');
    exit();
}

/*creo estas variables para cuando el usuario chequee lo de remember me, para
ponerlo en el html (que se autocompleten cuando haga un logout)*/
$username = isset($_COOKIE['login_username']) ? $_COOKIE['login_username'] : '';
$password = isset($_COOKIE['login_password']) ? $_COOKIE['login_password'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //verificamos si existe el usuario en la base de datos
    $sql = 'SELECT * FROM users WHERE username = :username AND password = :password';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username, 'password' => $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); //esto solo se pone cuando es un select

    //si existe el usuario
    if ($user) {
        $_SESSION['user'] = $user['username'];

        //si chequea lo de recuerdame, crea cookies
        if (isset($_POST['remember'])) {
            setcookie('login_username', $username, time() + (86400 * 30), "/"); // 30 días
            setcookie('login_password', $password, time() + (86400 * 30), "/"); // 30 días
        } else {
            //si no lo selecciona, se destruyen
            setcookie('login_username', '', time() - 3600, "/");
            setcookie('login_password', '', time() - 3600, "/");
        }

        //entra en la lista de tareas
        header('Location: ToDoList.php');
        exit;
    } else{
        $error = "Login failed.";
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login - TO-DO List</title>
</head>
<body>
    <div class="container">
        <h1>TO-DO List</h1>
        <form action="login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember" <?php echo isset($_COOKIE['login_username']) ? 'checked' : ''; ?>>
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)): ?>
        <p  style="color: palevioletred;"><?php echo $error; ?></p>
        <?php endif; ?>
        <p>Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
</body>
</html>

