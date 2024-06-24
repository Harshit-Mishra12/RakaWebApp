<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit; // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Import database
    include("../connection.php");

    // Retrieve form data
    $title = $_POST["title"];
    $docid = $_POST["docid"];
    $nop = $_POST["nop"];
    $date = $_POST["date"];
    $slots_start = $_POST["slot_start"]; // Array of slot start times
    $slots_end = $_POST["slot_end"]; // Array of slot end times
    // echo "Slots Start: <br>";
    // print_r($_POST["slot_start"]); // Debugging output
    // print_r($_POST["slot_end"]);
    // exit;
    // Insert into schedule table
    $sql = "INSERT INTO schedule (docid, title, scheduledate, nop) VALUES ('$docid', '$title', '$date', '$nop')";
    $result = $database->query($sql);

    if ($result) {
        $scheduleid = $database->insert_id; // Get the ID of the inserted schedule
        echo "New schedule ID: " . $scheduleid . "<br>"; // Debugging output

        // Check if the schedule ID exists in the schedule table
        $check_sql = "SELECT * FROM schedule WHERE scheduleid = '$scheduleid'";
        $check_result = $database->query($check_sql);

        if ($check_result->num_rows == 0) {
            echo "Schedule ID $scheduleid does not exist in the schedule table.";
            exit;
        }

        // Insert slots into schedule_slots table
        for ($i = 0; $i < count($slots_start); $i++) {
            $starttime = $slots_start[$i];
            $endtime = $slots_end[$i];
            echo "Start time: " .  $starttime . "<br>"; // Debugging output
            echo "End Time: " .  $endtime . "<br>"; // Debugging output
            echo "id check: " . $scheduleid . "<br>"; // Debugging output
            echo "count: " . count($slots_start) . "<br>"; // Debugging output

            $current_time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO schedule_slots (scheduleid, starttime, endtime, created_at, updated_at)
                    VALUES ('$scheduleid', '$starttime', '$endtime', '$current_time', '$current_time')";
            $result_slots = $database->query($sql);
            if (!$result_slots) {
                // Rollback if any slot insertion fails
                $delete_sql = "DELETE FROM schedule WHERE scheduleid = '$scheduleid'";
                $database->query($delete_sql);
                echo "Error inserting slot: " . $database->error;
                exit;
            }
        }

        header("location: schedule.php?action=session-added&title=$title");
        exit; // Stop further execution
    } else {
        echo "Error inserting schedule: " . $database->error;
    }
}
?>
