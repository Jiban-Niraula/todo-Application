<?php
session_start();
ob_start(); // Start output buffering


error_reporting(E_ALL);
ini_set('display_errors', 1);
    

function connectdb(){
     // Create a new database connection
    $conn = new mysqli('localhost', 'root', '', 'userdemo');

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;

}

function cleanData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    $task = cleanData($_POST['task']);

    if (empty($task)) {
        $_SESSION['empty'] = "Empty task";
        header('location:'.$_SERVER['PHP_SELF']);
        exit();
    }

    $conn = connectdb();
   
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO todo (task) VALUES (?)");
    $stmt->bind_param("s", $task); // "s" indicates the type (string)

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['insert_msg'] = "The data has been inserted successfully.";
    } else {
        $_SESSION['insert_msg_failed'] = "Error: " . $stmt->error; // Capture any error
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the same page or another page
    header('location:'.$_SERVER['PHP_SELF']);
    exit();
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
    
    <div class="card ms-4 mt-4 me-4 ">
        <div class="card-header alert alert-success">
            <h2 class=""><b>Todo Application</b></h2>
        </div>
        <div class="card-body">

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

        <?php
            if(isset($_SESSION['insert_msg'])){
                echo '<div class=" text-success mb-2 float-end" >'.$_SESSION['insert_msg']. '</div>';
                unset($_SESSION['insert_msg']);
            }
        echo '<label for="task" class="fs-4 mb-2">Add a <span class="text-success custom-bold">new Task</span></label>';

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


    <div class="card mt-3 ms-4 me-4">
    <div class="card-body">
        <div class="fw-bold text-center alert-success fs-2 text-success">Current Tasks</div>

        <div class="alert bg-light d-flex ">
            <form action="" method="get">
                <input type="submit" value="All" name="showAll" class="btn btn-outline-info btn-sm me-2" />
                <input type="submit" value="Completed" name="showCompleted" class="btn btn-outline-success btn-sm me-2" />
                <input type="submit" value="Pending" name="showPending" class="btn btn-outline-warning btn-sm" />
            </form>
        </div>

       

        <?php
           
                 // Create a new database connection
                 $conn = new mysqli('localhost', 'root', '', 'userdemo');

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

                        echo "<form action='" . $_SERVER['PHP_SELF'] ."'method='POST'>";
                        echo "<input type='submit' value='Update' class='btn btn-success btn-sm mx-1'/>";
                        echo "<input type='submit' name='delete' value='Delete' class='btn btn-danger btn-sm mx-1'/>";
                        echo "<input type='hidden' name='delete_id' value='".$row['id']. "'/>"; //stors the id of the data
                        echo "</form>";
                        
                        //for next column
                        $combtn=$row['is_completed'];
                        if($combtn == 0){
                            echo "<td>";
                            echo "<form action='" . $_SERVER['PHP_SELF'] ."'method='POST'>";
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

        
            if(isset($_POST['delete'])){
                $id = intval($_POST['delete_id']);
                $conn = connectdb();
                $delsql ="UPDATE todo SET is_deleted = 1 WHERE id = $id";
                mysqli_query($conn,$delsql);
                header('location:'.$_SERVER['PHP_SELF']);
                }

            if(isset($_POST['setCompleted'])){
                $id = intval($_POST['id']);
                $setCompletesql ="UPDATE todo SET is_completed = 1 WHERE id = $id";
                mysqli_query($conn,$setCompletesql);
                header('location:'.$_SERVER['PHP_SELF']);
                }
    
            if(isset($_GET['update'])){
                $id = intval($_GET['id']);
                
            }
            ob_end_flush();
        ?>
</body>
</html>

    </div>
</body>
</html>