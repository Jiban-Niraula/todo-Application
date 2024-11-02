<?php
session_start();
require_once('config.php');
$conn = connectdb();

if (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $delsql = "UPDATE todo SET is_deleted = 1 WHERE id = ?";
    
    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare($delsql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['delete_msg'] = "Task has been deleted successfully.";
    } else {
        $_SESSION['delete_msg_failed'] = "Error preparing statement: " . $conn->error;
    }
    header('Location: index.php');
    exit();
}

if (isset($_POST['setCompleted'])) {
    $id = intval($_POST['id']);
    $setCompletesql = "UPDATE todo SET is_completed = 1 WHERE id = ?";

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare($setCompletesql);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['complete_msg'] = "Task marked as completed.";
    } else {
        $_SESSION['complete_msg_failed'] = "Error preparing statement: " . $conn->error;
    }
    header('Location: index.php');
    exit();
}

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $updateviewsql = "SELECT * FROM todo WHERE id = $id";
    $result = $conn->query($updateviewsql);
    $row = $result->fetch_assoc();
    if ($result->num_rows > 0) {    
        $task = $row['task'];
        $id = $row['id'];
        $_SESSION['id'] = $row['id'];
        $_SESSION['task'] = $row['task'];
        $_SESSION['update'] = true; // Set update flag for index.php
        }
    } 
    header('Location: index.php');
    exit();
?>
