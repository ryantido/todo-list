<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "ryan", "Leslyspurple3.0", "to");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["addtask"])) {
    $task = $conn->real_escape_string($_POST["tasks"]); // Échapper les entrées utilisateur
    $conn->query("INSERT INTO tasks (title, statut) VALUES ('$task', 'pending')");
    header("Location: index.php");
    exit;
}

if (isset($_GET["delete"])) {
    $id = intval($_GET["delete"]);
    $conn->query("DELETE FROM tasks WHERE id = $id");
    header("Location: index.php");
    exit;
}

if (isset($_GET["complete"])) {
    $id = intval($_GET["complete"]); 
    $conn->query("UPDATE tasks SET statut = 'completed' WHERE id = $id");
    header("Location: index.php");
    exit;
}

$result = $conn->query("SELECT * FROM tasks ORDER BY statut ");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TODO LIST</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Todo list</h1>
        <form action="index.php" method="post">
            <input type="text" name="tasks" class="input-sub" placeholder="Ajouter une tâche" id="tasks" required>
            <input type="submit" name="addtask" class="sub" value="Ajouter">
        </form>
        <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <strong 
                    style="<?php echo $row['statut'] === 'completed' ? 'text-decoration: line-through; font-weight: bolder; font-style: italic;' : ''; ?>">
                    <?php echo htmlspecialchars($row["title"]); ?>
                </strong>
                <div>
                    <?php if ($row['statut'] !== 'completed'){ ?>
                        <a href="index.php?complete=<?php echo $row['id']; ?>" class="action">complete</a>
                    <?php }else{ ?>
                        <a class="action" style="color: seagreen; pointer-events: none;">completed</a>
                    <?php } ?>
                    <a href="index.php?delete=<?php echo $row['id']; ?>" style="color: #ff0000;">delete</a>
                </div>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>