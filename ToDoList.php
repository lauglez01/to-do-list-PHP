<?php
session_start();

require_once "conexionPDO.php";
$pdo = conexionPDO();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['user']; // guardamos el nombre de usuario que se loguea

// obtener el ID del usuario basado en el nombre de usuario
$sql = 'SELECT id FROM users WHERE username = :username';
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user === false) {
    echo 'Error: Usuario no encontrado.';
    exit();
}

$user_id = $user['id']; // guardamos el id del usuario

// agregar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $description = $_POST['task']; // la descripcion es lo que introduce el usuario

    $sql = 'INSERT INTO tasks(user_id, description, completed) VALUES (:user_id, :description, 0)'; // el 0 significa false
    $stmt = $pdo->prepare($sql);
    try {
        $stmt->execute(['user_id' => $user_id, 'description' => $description]); // no añadimos el parametro de completed porque ya lo hemos puesto por defecto
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

    // Redirigir para evitar el reenvío del formulario
    header('Location: ToDoList.php?success=1');
    exit();
}

// marcar tarea como completada o no completada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    $sql = 'UPDATE tasks SET completed = NOT completed WHERE id = :task_id AND user_id = :user_id'; //dependiendo de si esta checked o no, se cambia al contrario
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['task_id' => $task_id, 'user_id' => $user_id]);

    // Redirigir para evitar el reenvío del formulario
    header('Location: ToDoList.php');
    exit();
}

// eliminar tarea

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task_id'])) {
    $task_id = $_POST['delete_task_id'];
    $sql = 'DELETE FROM tasks WHERE id = :task_id AND user_id = :user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['task_id'=>$task_id, 'user_id'=>$user_id]);

        // Redirigir para evitar el reenvío del formulario
        header('Location: ToDoList.php');
        exit();
}


// obtener tareas del usuario
try {
    $sql = 'SELECT * FROM tasks WHERE user_id = :user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($tasks === false) {
        throw new Exception('Error al obtener las tareas.');
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
    $tasks = [];
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css?v=3">
    <title>Dashboard - TO-DO List</title>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
        <form id="task-form" action="ToDoList.php" method="POST">
            <input type="text" name="task" placeholder="Add a new task..." required>
            <button type="submit">Add</button>
        </form>
        <div id="error-message" style="color: red;"></div>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li>
                    <form action="ToDoList.php" method="POST" style="display:inline;">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <input type="checkbox" id="task-<?php echo $task['id']; ?>" <?php echo $task['completed'] ? 'checked' : ''; ?> onclick="this.form.submit()">
                    </form>
                    <label for="task-<?php echo $task['id']; ?>" style="text-decoration: <?php echo $task['completed'] ? 'line-through' : 'none'; ?>;">
                        <?php echo htmlspecialchars($task['description']); ?>
                    </label>
                    <form action="ToDoList.php" method="POST" style="display:inline;">
                        <input type="hidden" name="delete_task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>
</body>
</html>