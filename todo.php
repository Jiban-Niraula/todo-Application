<?php
session_start();

    function cleanData($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if(isset($_POST['submit'])){
        $task = cleanData($_POST['task']);

        if(empty($task)){
            $_SESSION['empty']="Please Enter a task";
            header('location:'.$_SERVER['PHP_SELF']);
            exit();
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
    .custom-bold {
        font-weight: bold;
    }
</style>
</head>
<body>
    
    <div class="card ms-4 mt-2">
        <div class="card-header alert alert-success">
            <h2 class=""><b>Todo Application</b></h2>
        </div>
        <div class="card-body">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="task" class="fs-4 mb-2">Add a <span class="text-success custom-bold">new Task</span></label>

        <?php

            if(isset($_SESSION['empty'])){
                echo '<div class=" text-danger mb-2" >'.'<sup>'.'*'.'</sup>'.$_SESSION['empty']. '</div>';
                unset($_SESSION['empty']);
            }

        ?>
        <input type="text" class="form-control" id="task" name="task" placeholder="Enter a new task">

        <input type="submit" name="submit" value="Add" class="btn btn-outline-success mt-2">
        </form>
        </div>
    </div>

    <div class="card mt-3 ms-4">
        <div class="card-body">
            <div for="text" class="fw-bold text-center alert-success fs-2 text-success">Current Tasks</div>


        </div>
    </div>
</body>
</html>