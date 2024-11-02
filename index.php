<?php

session_start();
ob_start(); // Start output buffering
require_once('config.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);
    
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

<link rel="icon" type="image/x-icon" href="list.png">
</head>
<body>
    
    <div class="card ms-4 mt-4 me-4 ">
        <div class="card-header alert alert-success">
            <h2 class=""><b>Todo Application</b></h2>
        </div>
        <div class="card-body">

       

        <?php
            
             echo '<label for="task" class="fs-4 mb-2">Add a <span class="text-success custom-bold">new Task</span></label>';

           

            if(empty($_SESSION['update'])){
                
                echo "<form action='create.php' method='POST'>";

                if(isset($_SESSION['insert_msg'])){
                echo '<div class=" text-success mb-2 float-end" >'.$_SESSION['insert_msg']. '</div>';
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
                echo "<form action='update.php' method='POST'>";
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


    <div class="card mt-3 ms-4 me-4">
    <div class="card-body">
        <div class="fw-bold text-center alert-success fs-2 text-success">Current Tasks</div>

        <div class="alert bg-light d-flex ">
            <form action="actionbuttons.php" method="get">
                <input type="submit" value="All" name="showAll" class="btn btn-outline-info btn-sm me-2" />
                <input type="submit" value="Completed" name="showCompleted" class="btn btn-outline-success btn-sm me-2" />
                <input type="submit" value="Pending" name="showPending" class="btn btn-outline-warning btn-sm" />
            </form>
        </div>

       

        <?php
           
                 // Create a new database connection
                 $conn = connectdb();
                 if (isset($_GET['showAll']) || empty($_GET)) {
                    $allsql = "SELECT * FROM todo WHERE is_deleted='0' ORDER BY id DESC";
                    showresult($conn, $allsql);
                }elseif(isset($_GET['showCompleted'])){
                    $completedsql = "SELECT * FROM todo WHERE is_completed = '1' AND  is_deleted='0' ORDER BY id DESC";
                    showresult($conn,$completedsql);
                }elseif(isset($_GET['showPending'])){
                    $pendingsql = "SELECT * FROM todo WHERE is_deleted='0' AND  is_completed = '0' ORDER BY id DESC";
                    showresult($conn,$pendingsql);
                }

            
                function showresult($conn,$sql){
                    echo "<table class='table'>
                    <thead>
                    <th style='width:7%'>S.N.</th>
                    <th style='width:30%'>Task</th>
                    <th style='width:20%'>Status</th>
                    <th style='width:40%' class='text-center'>Action</th>
                    <th style='width:10%' class='text-center'></th>
                    </thead>
                    <tbody>";
                    $result = mysqli_query($conn, $sql);
                    $sn =0;
                    if($result){
                        if($numrows = mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_assoc($result)){
                            $sn++;
                            echo "<tr>";
                            echo "<td style='text-align:left'>".$sn."</td>";
                            echo "<td style='text-align:left'>".$row['task']."</td>";
            
                            $status=$row['is_completed'];
                            if($status == 0){
                                echo "<td class='text-warning'>Pending</td>";
                            }else{
                                echo "<td class='text-success'>Completed</td>";
                            } 
                           
                            
                            echo "<td class='text-center'>";
                            echo "<div class='d-flex justify-content-center'>";
            
                            echo "<form action='actionbuttons.php' method='POST'>";
                            echo "<input type='submit' name='update' value='Update' class='btn btn-success btn-sm mx-1'/>";
                            echo "<input type='submit' name='delete' value='Delete' class='btn btn-danger btn-sm mx-1'/>";
                            echo "<input type='hidden' name='id' value='".$row['id']. "'/>"; //stors the id of the data
                            echo "</form>";
                            
                            //for next column
                            $combtn=$row['is_completed'];
                            if($combtn == 0){
                                echo "<td>";
                                echo "<form action='actionbuttons.php' method='POST'>";
                                echo "<input type='hidden' name='id' value='".$row['id']. "'/>"; //stors the id of the data
                                echo "<input type='submit' name='setCompleted' value='Set as Completed' class='float-end btn btn-outline-success btn-sm me-2'/>
                                </form> </td>";
                            }else
                            echo "<td></td>
                            </td>";
                           echo "</tr>";
                           
                           
                       
                        }
                    }
                    }
            
                    echo "</tbody>";
                    echo "</table>";
                }
            
                


             
 
                

        
         
            ob_end_flush();
        ?>
</body>
</html>

    </div>
</body>
</html>