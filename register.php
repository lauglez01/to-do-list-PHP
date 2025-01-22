<?php

require_once "conexionPDO.php";
$pdo = conexionPDO();

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = 'SELECT * FROM users WHERE username = :username';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si el usuario ya existe, mostrar un mensaje de error
    if ($user) {
        $error = "Username already exists. Please choose another one.";
    } else {
        // Si no existe el usuario, se inserta en la base de datos
        $sql = 'INSERT INTO users (username, password) VALUES (:username, :password)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username, 'password' => $password]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Sign Up</title>
</head>

<body>
    <div class="container">
        <h1>Sign Up</h1>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>

            <?php if (isset($error)): ?>
                <p style="color: palevioletred;"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <p>Already have an account? <a href="login.php">Log in</a></p>
    </div>
</body>

</html>