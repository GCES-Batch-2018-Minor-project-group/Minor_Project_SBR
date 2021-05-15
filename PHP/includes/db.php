<?php

include("table_columns_name.php");
include("encryption.php");

$hostName = "localhost";
$username = "root";
$password = "";
$dbName = "sbr_database";

$conn = new mysqli($hostName, $username, $password);

// $conn->query('DROP DATABASE myDB');

// connecting to localhost
if($conn->connect_error){
    die('connection failed: '.$conn->connect_error);
}else{

    // using the database if database present
    $selectDB = "USE ". $dbName;
    
    // creating the database if database not present
    if($conn->query($selectDB) === FALSE){
        $createDB = "CREATE DATABASE ".$dbName;

        if($conn->query($createDB) === FALSE){
            die('some error while creating database');
        }
    }

    // connecting to the database in the localhost
    $con = new mysqli($hostName, $username, $password, $dbName);

    if($con->connect_error){
        die("Unable to connect to database");
    }

    // checking if users table already exits
    $checkUserTable = "SELECT * FROM users";

    // creating a new users table if not present already
    if($con->query($checkUserTable) === FALSE){
        $createUserTable = 
            "CREATE TABLE users(
                uId int auto_increment primary key,
                $username_column varchar(50) not null,
                $email_column text not null unique,
                $phoneNumber_column text not null unique,
                $citizenship_column text not null unique,
                $password_column text not null,
                $emailVerification_column enum('verified', 'not verified') not null,
                $activation_column text not null,
                $createdAt_column timestamp,
                $image_column varchar(100)
            );
            ";
        
        if($con->query($createUserTable) === FALSE){
            die("Error while creating users table");
        }
    }

    $str = "/6G6F;WvK7;s{au/6G6F;WvK7;s{au";
    $key = md5($str);

    $admin1Email = encryptData('Magar33alson@gmail.com', $key, $str);
    $admin2Email = encryptData('salipagurung@gmail.com', $key, $str);
    $admin3Email = encryptData('correctyyyy@gmail.com', $key, $str);

    $admin1Pass = password_hash('alson123456', PASSWORD_DEFAULT);
    $admin2Pass = password_hash('salipa123456', PASSWORD_DEFAULT);
    $admin3Pass = password_hash('pratigya123456', PASSWORD_DEFAULT);

    $admin1Check = "SELECT * FROM users WHERE $email_column = '$admin1Email' AND $password_column = '$admin1Pass'";

    if($con->query($admin1Check) === FALSE){
        $createAdmin1 = "INSERT INTO users VALUES
                    (null, 'Alson Garbuja', '$admin1Email', 'admin1', 'admin1', '$admin1Pass', 'verified', 'admin1', null, null)";

        if($con->query($createAdmin1) === FALSE){
            die("Error while creating admin 1");
        }
    }

    $admin2Check = "SELECT * FROM users WHERE $email_column = '$admin2Email' AND $password_column = '$admin2Pass'";

    if($con->query($admin2Check) === FALSE){
        $createAdmin2 = "INSERT INTO users VALUES
                    (null, 'Salipa Gurung', '$admin2Email', 'admin2', 'admin2', '$admin2Pass', 'verified', 'admin2', null, null)";

        if($con->query($createAdmin2) === FALSE){
            die("Error while creating admin 2");
        }
    }

    $admin3Check = "SELECT * FROM users WHERE $email_column = '$admin3Email' AND $password_column = '$admin3Pass'";

    if($con->query($admin2Check) === FALSE){
        $createAdmin3 = "INSERT INTO users VALUES
                    (null, 'Pratigya Dhakal', '$admin3Email', 'admin3', 'admin3', '$admin3Pass', 'verified', 'admin3', null, null)";

        if($con->query($createAdmin3) === FALSE){
            die("Error while creating admin 2");
        }
    }

    // checking if vehicles_data table is present
    $checkVehicleTable = "SELECT * FROM vehicles_data";

    // creating vehicles_data table if not already present
    if($con->query($checkVehicleTable) === FALSE){
        $createVehicleTable = 
            "CREATE TABLE vehicles_data(
                vId int auto_increment primary key,
                uId int not null,
                $vehicleType_column enum('two wheeler', 'four wheeler') not null,
                $vehicleCategory_column enum('A','B','K') not null,
                $engineCC_column varchar(100) not null,
                $vehicleRegistration_column text not null unique,
                $pp_column enum('public','private') not null,
                $createdAt_column timestamp,
                FOREIGN KEY (uId) REFERENCES users(uId)
            );
            ";

        if($con->query($createVehicleTable) === FALSE){
            die('Error while creating vehicle table');
        }
    }

    $checkFormsTable = "SELECT * FROM forms_data";

    if($con->query($checkFormsTable) === FALSE){
        $createFormsTable = 
            "CREATE TABLE forms_data(
                fId int auto_increment primary key,
                uId int,
                name varchar(50) not null,
                $citizenship_column text not null,
                $phoneNumber_column text not null,
                $vehicleType_column enum('two wheeler','four wheeler') not null,
                $vehicleCategory_column enum('A','B','K') not null,
                $engineCC_column varchar(100) not null,
                $pp_column enum('public', 'private') not null,
                $insurance_column enum('yes', 'no') not null,
                $vehicleRegistration_column text not null,
                $renewDate_column date not null,
                $createdAt_column timestamp,
                $formFillerName_column varchar(50) not null,
                $formFillerVehicleReg_column text not null,
                $formFillerPhn_column text not null,
                $formFillerId_column int not null,
                FOREIGN KEY ($formFillerId_column) REFERENCES users(uId)
            );
            ";

        if($con->query($createFormsTable) === FALSE){
            die('Error while creating forms table');
        }
    }

    $checkTaxTable = "SELECT * FROM tax_details";

    if($con->query($checkTaxTable) === FALSE){
        $createTaxTable = 
            "CREATE TABLE tax_details(
                tId int auto_increment primary key,
                uId int,
                $taxAmount_column varchar(100) not null,
                $fineAmount_column varchar(100) not null,
                $finePercentage_column varchar(50) not null,
                $paidIn_column varchar(100) not null,
                $totalAmount_column varchar(100) not null,
                $createdAt_column timestamp,
                $fillerId_column int not null,
                FOREIGN KEY ($fillerId_column) REFERENCES users(uId)
            );
            ";

        if($con->query($createTaxTable) === FALSE){
            die("Error while creating tax table");
        }
    }
}

?>
