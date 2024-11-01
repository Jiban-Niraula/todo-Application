Name of DATABASE = userdemo
Name of table = todo.

Execute follwing sql query before running the application

Create DATABASE userdemo; //This creates a database

//Now to create a table
CREATE TABLE your_table_name (
    id INT(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    task VARCHAR(200) DEFAULT NULL,
    is_deleted TINYINT(1) DEFAULT 0,
    is_completed TINYINT(1) DEFAULT 0,
    created_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
);
