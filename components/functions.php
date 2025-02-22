<?php
// add_task.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $due_date]);
    
    header('Location: index.php');
    exit;
}

<?php
// delete_task.php
require 'config.php';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    
    header('Location: index.php');
    exit;
}