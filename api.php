<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'ebpearls';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Ensure completed column exists
$conn->query("ALTER TABLE tasks 
    ADD COLUMN IF NOT EXISTS completed TINYINT(1) DEFAULT 0");
$conn->query($sql);

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new task
    if (isset($_POST['add_task'])) {
        $task = mysqli_real_escape_string($conn, $_POST['new_task']);
        if (!empty($task)) {
            $sql = "INSERT INTO tasks (task_name) VALUES ('$task')";
            $conn->query($sql);
        }
    }
    
    // Toggle task status
    if (isset($_POST['toggle_task'])) {
        $task_id = (int)$_POST['toggle_task'];
        $completed = isset($_POST['completed']) ? 1 : 0;
        $sql = "UPDATE tasks SET completed = $completed WHERE id = $task_id";
        $conn->query($sql);
    }
    
    // Delete completed tasks
    if (isset($_POST['delete_completed'])) {
        $sql = "DELETE FROM tasks WHERE completed = TRUE";
        $conn->query($sql);
    }
}

// Get all tasks
$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $conn->query($sql);
$tasks = $result->fetch_all(MYSQLI_ASSOC);

// Split tasks
$firstTask = count($tasks) > 0 ? $tasks[0] : null;
$otherTasks = count($tasks) > 1 ? array_slice($tasks, 1) : [];

// Count completed tasks
$completedCount = $conn->query("SELECT COUNT(*) AS count FROM tasks WHERE completed = TRUE")->fetch_assoc()['count'];

// Close PHP tag to output HTML
?>
<!DOCTYPE html>
<html>
<body>
    <div class="tm-wrapper">
        <div class="tm-container">
            <div class="tm-header">
                <h1>Task Manager</h1>
                <p>Your daily to-do list</p>
            </div>

            <div class="tm-main">
                <div class="tm-task-flex">
                    <?php if ($firstTask): ?>
                    <div class="tm-task-item">
                        <form method="POST">
                            <input type="hidden" name="toggle_task" value="<?= $firstTask['id'] ?>">
                            <input 
                                type="checkbox" 
                                class="tm-checkbox" 
                                name="completed" 
                                <?= $firstTask['completed'] ? 'checked' : '' ?>
                                onchange="this.form.submit()"
                            >
                        </form>
                        <label class="tm-task-label <?= $firstTask['completed'] ? 'completed' : '' ?>">
                            <?= htmlspecialchars($firstTask['task_name']) ?>
                        </label>
                    </div>
                    <?php endif; ?>

                    <?php if ($completedCount > 0): ?>
                    <form method="POST">
                        <button type="submit" name="delete_completed" class="tm-delete-btn">
                            <i data-lucide="trash-2"></i>
                            Delete (<?= $completedCount ?>)
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

                <?php foreach ($otherTasks as $task): ?>
                <div class="tm-task-item">
                    <form method="POST">
                        <input type="hidden" name="toggle_task" value="<?= $task['id'] ?>">
                        <input 
                            type="checkbox" 
                            class="tm-checkbox" 
                            name="completed" 
                            <?= $task['completed'] ? 'checked' : '' ?>
                            onchange="this.form.submit()"
                        >
                    </form>
                    <label class="tm-task-label <?= $task['completed'] ? 'completed' : '' ?>">
                        <?= htmlspecialchars($task['task_name']) ?>
                    </label>
                </div>
                <?php endforeach; ?>

                <form method="POST" class="tm-add-task-form">
                    <input
                        type="text"
                        name="new_task"
                        placeholder="Add new task"
                        class="tm-add-input"
                        required
                    >
                    <button type="submit" name="add_task" class="tm-add-btn">
                        Add Task
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
// Re-open PHP tag if needed for closing connection
$conn->close(); 
?>