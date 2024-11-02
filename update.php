<?php
session_start();
require_once('config.php');

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['updatetask'])) {
    $id = cleanData($_POST['id']);
    $task = cleanData($_POST['updatedtask']);


    if (empty($task)) {
        $_SESSION['empty'] = "Task cannot be empty.";
        header('Location: index.php');
        exit();
    }

    $conn = connectdb();

     // Ensure task is properly escaped and quoted$task = mysqli_real_escape_string($conn, $task); // Escape the task to prevent SQL injection
    
    $sql = "UPDATE todo SET task = '$task' WHERE id = $id";
    if(mysqli_query($conn,$sql))
    {
        $_SESSION['insert_msg'] = "The task has been updated successfully.";
    } 
    } else {
        $_SESSION['insert_msg_failed'] = "Error While Updating Data ";
    }

   

    // Redirect back to index page
    header('Location: index.php');
    exit();

?>
