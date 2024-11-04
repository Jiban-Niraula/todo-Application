<?php
session_start();
require_once('config.php');

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $task = cleanData($_POST['task']);

    if (empty($task)) {
        $_SESSION['empty'] = "Task cannot be empty.";
        header('Location: ../views/new.php');
        exit();
    }

    $conn = connectdb();

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO todo (task) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $task);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $_SESSION['insert_msg'] = "The task has been added successfully.";
        } else {
            $_SESSION['insert_msg_failed'] = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $_SESSION['insert_msg_failed'] = "Error preparing statement: " . $conn->error;
    }

    // Close the database connection
    $conn->close();

    // Redirect back to index page
    header('Location: ../views/index.php');
    exit();
}
?>
