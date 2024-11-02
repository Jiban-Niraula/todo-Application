<?php
function connectdb(){
    // Create a new database connection
   $conn = new mysqli('localhost', 'root', '', 'userdemo');

   // Check for connection errors
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   return $conn;

}
?>