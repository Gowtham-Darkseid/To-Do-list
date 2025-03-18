<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sigmar&display=swap');
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url(bg.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, sans-serif;
            position: relative;
        }
        .signout {
            position: absolute;
            top: 20px;
            right: 20px;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .title {
            font-family: "Sigmar", sans-serif;
            font-weight: 400;
            font-style: normal;
            color: rgba(255, 255, 255, 0.734);
            font-size: 50px;
            margin-bottom: 20px;
        }
        .container {
            padding: 30px;
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(8.5px);
            border-radius: 10px;
            width: 400px;
        }
        .input-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        input {
            flex: 1;
            padding: 10px;
            border: 1px solid #cccccc5e;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            border: 0;
            transition: all 0.3s;
        }
        button:hover {
            background-color: hsl(261deg 80% 48%);
            color: white;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8.5px);
            border-radius: 5px;
            font-size: 16px;
            margin-top: 10px;
        }
        li.completed {
            text-decoration: line-through;
            color: gray;
        }
        .edit, .delete {
            background: none;
            border: none;
            cursor: pointer;
        }
        .edit { color: blue; }
        .delete { color: red; }
    </style>
</head>
<body>
    <button class="signout" onclick="signOut()">Sign Out</button>
    <div class="title">TO-DO LIST 📝</div>
    <div class="container">
        <form action="todo.php" method="POST">
            <div class="input-container">
                <input type="text" name="task" placeholder="Add your task">
                <button type="submit" name="add">ADD</button>
            </div>
        </form>
        <ul id="taskList">
            <ul>
                <?php
$conn = new mysqli("localhost", "root", "", "todo_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $task = trim($_POST["task"]);
        if (!empty($task)) {
            $stmt = $conn->prepare("INSERT INTO tasks (text) VALUES (?)");
            $stmt->bind_param("s", $task);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST["toggle"])) {
        $taskId = $_POST["toggle"];
        $conn->query("UPDATE tasks SET completed = NOT completed WHERE id = $taskId");
    } elseif (isset($_POST["delete"])) {
        $taskId = $_POST["delete"];
        $conn->query("DELETE FROM tasks WHERE id = $taskId");
    }
}

$result = $conn->query("SELECT * FROM tasks ORDER BY id DESC");

while ($row = $result->fetch_assoc()): ?>
    <li class="<?= $row['completed'] ? 'completed' : '' ?>">
        <form method="POST" action="todo.php" style="display:inline;">
            <button type="submit" name="toggle" value="<?= $row['id'] ?>">✔</button>
        </form>
        <?= htmlspecialchars($row['text']) ?>
        <form method="POST" action="todo.php" style="display:inline;">
            <button type="submit" name="delete" value="<?= $row['id'] ?>">✖</button>
        </form>
    </li>
<?php endwhile;
?>

            </ul>
        </ul>
    </div>
</body>
</html>
