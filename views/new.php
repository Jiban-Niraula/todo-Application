<?php
session_start();
include '../layouts/navbar.php';?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create : Todo </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="../FORM-DATABASE/assets/list.png">
</head>
<body>
    
    
<div class="card ms-4 mt-4 me-4 ">
        <div class="card-header alert alert-success">
            <h2 class=""><b>Create a new Task</b></h2>
            <p>You are about to create a new task. Please write the name of task below.</p>
        </div>
        <div class="card-body">

       

        <?php
            
             echo '<label for="task" class="fs-4 mb-2">Add a <span class="text-success fw-bolder">new Task</span></label>';

           

            if(empty($_SESSION['update'])){
                
                echo "<form action='../processes/create.php' method='POST'>";

                if(isset($_SESSION['insert_msg'])){
                echo '<div class=" text-success fw-bolder mb-2 float-end" >'.$_SESSION['insert_msg']. '</div>';
                unset($_SESSION['insert_msg']);
            }

                 if(isset($_SESSION['empty'])){
                echo '<div class=" text-danger mb-2" >'.'<sup>'.'*'.'</sup>'.$_SESSION['empty']. '</div>';
                unset($_SESSION['empty']);
            }
                echo "<input type='text' class='form-control' id='task' name='task' placeholder='Enter a new task'>
                <input type='submit' name='submit' value='Add' class='btn btn-outline-success mt-2'>
                </form>"; 
                 
            }elseif(isset($_SESSION['update'])){
                $id = $_SESSION['id'];
                echo "<form action='../processes/update.php' method='POST'>";
                echo  "<input type='text' class='form-control' value='".$_SESSION['task']. "'name='updatedtask' placeholder='Update your task'>";
                echo "<input type='submit' name='updatetask' value='Update' class='btn btn-outline-success mt-2'>";
                echo  "<input type='hidden' name='id' value='".$id."'>";
                echo "</form>";
                unset($_SESSION['id']);
                unset($_SESSION['update']);
                unset($_SESSION['task']);
              }
        ?>

        
       
        </div>
        
    </div>

    <?php include '../layouts/footer.php';?>

</body>
</html>

