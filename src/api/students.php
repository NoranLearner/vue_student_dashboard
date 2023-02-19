<?php

header('Access-Control-Allow-Origin:*');

$result = array();
$result["error"] = false;
$result['message'] = "";

// Connect To Database
$host_name = "localhost";
$host_user = "root";
$host_pass = "";
$host_db = "student_dashboard";

// ************************************************ //

// Create connection
// $connect = new mysqli($host_name, $host_user, $host_pass);
$connect = mysqli_connect($host_name, $host_user, $host_pass);

if (isset($connect) & isset($host_db)) {
    $connect_db = mysqli_select_db($connect, $host_db);
    if ($connect_db) {
        $result["is_db_connected"] = true;
        $result["connection_msg"] = "Successfully Connected";
    } else {
        $result["is_db_connected"] = false;
        $result["connection_msg"] = "Database Name is not found";
    }
} else {
    $result["is_db_connected"] = false;
    $result["connection_msg"] = "Connection Setup is not correct";
}

// ************************************************ //

// Actions
$action = "";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

// ************************************************ //

if ($action == "read") {
    // $result["students"] = "hi";
    $sql = mysqli_query($connect, "select * from students");
    // Return the number of rows
    $sqlNumOfRows = mysqli_num_rows($sql);
    // There is no data
    if ($sqlNumOfRows < 1) {
        $result['message'] = "No student added yet";
        $result["error"] = true;
    }
    $studentArray = array();
    while ($rows = mysqli_fetch_assoc($sql)) {
        array_push($studentArray, $rows);
    }
    $result["students"] = $studentArray;
}

// ************************************************ //

if ($action == "create") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $sql_verify = mysqli_query($connect, "select * from `students` where email = '$email'");
    $find_sql_verify_num_of_rows = mysqli_num_rows($sql_verify);
    if ($find_sql_verify_num_of_rows < 1) {
        $sql = mysqli_query($connect, " insert into `students` (name, email, phone) VALUES ('$name', '$email', '$phone')");
        if ($sql) {
            $result['message'] = "Successfully added this student's record => $name ";
        } else {
            $result["error"] = true;
            $result['message'] = "Failed adding this student => $name";
        }
    } else {
        $result["error"] = true;
        $result['message'] = "This email: $email does exist, choose another email";
    }

}

// ************************************************ //

if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $sql_verify = mysqli_query($connect, "select * from `students` where email = '$email' and id != '$id' ");
    $find_sql_verify_num_of_rows = mysqli_num_rows($sql_verify);
    if ($find_sql_verify_num_of_rows < 1) {
        $sql = mysqli_query($connect, " update `students`
                                        SET 
                                        name = '$name',
                                        email = '$email',
                                        phone = '$phone',
                                        WHERE
                                        id = '$id',
                            ");
        if ($sql) {
            $result['message'] = "Successfully updated this student's record => $name ";
        } else {
            $result["error"] = true;
            $result['message'] = "Failed updating this student => $name";
        }
    } else {
        $result["error"] = true;
        $result['message'] = "This email: $email does exist, choose another email";
    }

}

// ************************************************ //

if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $sql_verify = mysqli_query($connect, "select * from `students` where id = '$id' ");
    $find_sql_verify_num_of_rows = mysqli_num_rows($sql_verify);
    if ($find_sql_verify_num_of_rows >= 1) {
        $sql = mysqli_query($connect, "delete from `students` where id = '$id'");
        if ($sql) {
            $result['message'] = "Successfully deleted this student's record => $name ";
        } else {
            $result["error"] = true;
            $result['message'] = "Failed deleting this student => $name";
        }
    } else {
        $result["error"] = true;
        $result['message'] = "This record does not exist";
    }
}

// ************************************************ //

if ($action == "deleteAll") {
    $sql = mysqli_query($connect, "delete from students");
    if ($sql) {
        $result['message'] = "Successfully deleted all students";
    } else {
        $result["error"] = true;
        $result['message'] = "Failed deleting all students";
    }
}

// ************************************************ //

// 
echo json_encode($result);

// Close the Connection
mysqli_close($connect);
