
---

### **Enunciado del Proyecto**
Diseña e implementa una aplicación web de lista de tareas (**TO-DO List**) utilizando sesiones, cookies y una base de datos. La aplicación debe cumplir con los siguientes requisitos:

1. **Inicio de sesión**:  
   Los usuarios deben iniciar sesión para acceder a su lista de tareas.  
   Las credenciales se almacenarán en la base de datos (usuarios con nombre de usuario y contraseña).  
   Usa sesiones para mantener al usuario autenticado mientras navega por la aplicación.

2. **Cookies**:  
   Implementa cookies para recordar al usuario durante un período de tiempo determinado (por ejemplo, 7 días) si selecciona la opción "Recordarme".

3. **Lista de tareas**:  
   - Cada usuario tendrá su lista de tareas privada, almacenada en la base de datos.
   - Los usuarios pueden agregar, editar, marcar como completada o eliminar tareas.

4. **Base de datos**:  
   Diseña una base de datos con las siguientes tablas:  
   - `users`: para almacenar la información de los usuarios.  
   - `tasks`: para almacenar las tareas, incluyendo un campo para indicar si están completadas.

5. **Estilo y diseño**:  
   Aplica estilos básicos con CSS para que la aplicación tenga una interfaz agradable.

---

A continuación, se presenta el código:

---

### **1. Estructura de Archivos**
- `index.html` (Pantalla de inicio y login)
- `dashboard.html` (Página principal de la lista de tareas)
- `styles.css` (Archivo de estilos)
- `schema.sql` (Esquema de la base de datos)

---

### **HTML: `index.html`**
```html
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
        <form action="/login" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/register">Sign Up</a></p>
    </div>
</body>
</html>
```

---

### **HTML: `dashboard.html`**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Dashboard - TO-DO List</title>
</head>
<body>
    <div class="container">
        <h1>Welcome, [Username]</h1>
        <form action="/add-task" method="POST">
            <input type="text" name="task" placeholder="Add a new task..." required>
            <button type="submit">Add</button>
        </form>
        <ul class="task-list">
            <li>
                <input type="checkbox" id="task-1">
                <label for="task-1">Sample Task</label>
                <a href="/delete-task?id=1" class="delete-btn">Delete</a>
            </li>
            <!-- Repeat tasks dynamically -->
        </ul>
        <a href="/logout" class="logout-btn">Logout</a>
    </div>
</body>
</html>
```

---

### **CSS: `styles.css`**
```css
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    color: #333;
}

.container {
    max-width: 400px;
    margin: 50px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #555;
}

form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

input[type="text"],
input[type="password"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px;
    border: none;
    background: #5cb85c;
    color: white;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background: #4cae4c;
}

ul.task-list {
    list-style: none;
    padding: 0;
}

ul.task-list li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

ul.task-list li label {
    margin-left: 10px;
}

.delete-btn {
    color: red;
    text-decoration: none;
    font-size: 0.9em;
}

.logout-btn {
    display: block;
    text-align: center;
    margin-top: 20px;
    color: #007bff;
    text-decoration: none;
}

.logout-btn:hover {
    text-decoration: underline;
}
```

---

### **SQL: `schema.sql`**
```sql
CREATE DATABASE todo_list;

USE todo_list;

-- Table for users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Table for tasks
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    completed BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

Puedes implementar el backend con un lenguaje como Python (Flask o Django), PHP o Node.js según tu preferencia. Si necesitas ayuda con el backend, ¡hazmelo saber! 😊