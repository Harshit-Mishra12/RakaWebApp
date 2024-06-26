<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">

    <title>Appointments</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
    <?php

    //learn from w3schools.com

    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }


    //import database
    include("../connection.php");
    $sqlmain = "select * from patient where pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];


    //echo $userid;
    //echo $username;


    //TODO
    $sqlmain = "select appointment.appoid,schedule.scheduleid,schedule.title,doctor.docname,patient.pname,schedule.scheduledate,schedule.scheduletime,appointment.apponum,appointment.appodate from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  patient.pid=$userid ";

    if ($_POST) {
        //print_r($_POST);




        if (!empty($_POST["sheduledate"])) {
            $sheduledate = $_POST["sheduledate"];
            $sqlmain .= " and schedule.scheduledate='$sheduledate' ";
        };



        //echo $sqlmain;

    }

    $sqlmain .= "order by appointment.appodate  asc";
    $result = $database->query($sqlmain);

    // Sample array data (replace this with your actual data logic)
    $dummyData = [
        [
            'appoid' => 1,
            'scheduleid' => 101,
            'title' => 'Appointment Title 1',
            'docname' => 'Dr. John Doe',
            'pname' => 'Patient A',
            'scheduledate' => '2024-06-25',
            'scheduletime' => '09:00',
            'apponum' => 1,
            'appodate' => '2024-06-25'
        ],
        [
            'appoid' => 2,
            'scheduleid' => 102,
            'title' => 'Appointment Title 2',
            'docname' => 'Dr. Jane Smith',
            'pname' => 'Patient B',
            'scheduledate' => '2024-06-26',
            'scheduletime' => '10:30',
            'apponum' => 2,
            'appodate' => '2024-06-26'
        ],
        [
            'appoid' => 3,
            'scheduleid' => 103,
            'title' => 'Appointment Title 3',
            'docname' => 'Dr. Michael Brown',
            'pname' => 'Patient C',
            'scheduledate' => '2024-06-27',
            'scheduletime' => '14:00',
            'apponum' => 3,
            'appodate' => '2024-06-27'
        ]
    ];
    function formatDate($date)
    {
        return date('Y-m-d', strtotime($date));
    }

    // Process form submission to filter appointments by date
    if (isset($_POST['filter'])) {
        $filterDate = $_POST['filter_date'];
        $filteredData = [];
        foreach ($dummyData as $appointment) {
            if (formatDate($appointment['scheduledate']) == formatDate($filterDate)) {
                $filteredData[] = $appointment;
            }
        }
    } else {
        // Initially show all appointments
        $filteredData = $dummyData;
    }
    ?>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-home">
                        <a href="index.php" class="non-style-link-menu ">
                            <div>
                                <p class="menu-text">Home</p>
                        </a>
        </div></a>
        </td>
        </tr>
        <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor">
                <a href="doctors.php" class="non-style-link-menu">
                    <div>
                        <p class="menu-text">All Doctors</p>
                </a>
    </div>
    </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-session">
            <a href="schedule.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Scheduled Sessions</p>
                </div>
            </a>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
            <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
                <div>
                    <p class="menu-text">My Bookings</p>
            </a></div>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-doctor">
            <a href="new-appointment.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Book Appointment</p>
            </a></div>
        </td>
    </tr>
    <tr class="menu-row">
        <td class="menu-btn menu-icon-settings">
            <a href="settings.php" class="non-style-link-menu">
                <div>
                    <p class="menu-text">Settings</p>
            </a></div>
        </td>
    </tr>

    </table>
    </div>
    <div class="dash-body">
        <table border="0" width="100%" style="border-spacing: 0; margin:0; padding:0; margin-top:25px;">
            <tr>
                <td width="13%">
                    <a href="index.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px; padding-bottom:11px; margin-left:20px; width:125px">
                            <font class="tn-in-text">Back</font>
                        </button></a>
                </td>
                <td>
                    <p style="font-size: 23px; padding-left:12px; font-weight: 600;">Booking Information</p>
                </td>
                <td width="15%">
                    <p style="font-size: 14px; color: rgb(119, 119, 119); padding: 0; margin: 0; text-align: right;">
                        Today's Date
                    </p>
                    <p class="heading-sub12" style="padding: 0; margin: 0;">
                        <?php
                        date_default_timezone_set('Asia/Kolkata');
                        $today = date('Y-m-d');
                        echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex; justify-content: center; align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                    <table class="filter-container" border="0">
                            <tr>
                                <td width="50%">
                                    <input type="date" class="input-text filter-container-input" id="dateInput" placeholder="Select Date" onchange="filterAppointments()">
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="93%" class="sub-table scrolldown" border="0" id="hospitalTable" style="border:none">
                                <thead>
                                    <tr>
                                        <th class="table-headin table-text-align">Appointment ID</th>
                                        <th class="table-headin table-text-align">Schedule ID</th>
                                        <th class="table-headin table-text-align">Title</th>
                                        <th class="table-headin table-text-align">Doctor Name</th>
                                        <th class="table-headin table-text-align">Scheduled Date</th>
                                        <th class="table-headin table-text-align">Schedule Time</th>
                                        <th class="table-headin table-text-align">Appointment Number</th>
                                        <th class="table-headin table-text-align">Appointment Date</th>
                                        <th class="table-headin table-text-align">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($filteredData as $appointment) : ?>
                                        <tr>
                                            <td class="table-text-align"><?php echo $appointment['appoid']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['scheduleid']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['title']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['docname']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['scheduledate']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['scheduletime']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['apponum']; ?></td>
                                            <td class="table-text-align"><?php echo $appointment['appodate']; ?></td>
                                            <td class="table-text-align">
                                                <button class="book-appointment-btn login-btn btn-primary-soft btn">
                                                    <a href="?action=drop&id=<?php echo $appointment['appoid']; ?>&title=<?php echo $appointment['title']; ?>&doc=<?php echo $appointment['docname']; ?>">
                                                        Cancel Booking
                                                    </a>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <p style="text-align:center;">Total Hospitals: <?php echo $hospital_count; ?></p>
                </td>
            </tr>
        </table>
    </div>
    </div>
    <?php

    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'booking-added') {

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                    <br><br>
                        <h2>Booking Successfully.</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                        Your Appointment number is ' . $id . '.<br><br>

                        </div>
                        <div style="display: flex;justify-content: center;">

                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                        </div>
                    </center>
            </div>
            </div>
            ';
        } elseif ($action == 'drop') {
            $title = $_GET["title"];
            $docname = $_GET["doc"];

            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="appointment.php">&times;</a>
                        <div class="content">
                            You want to Cancel this Appointment?<br><br>
                            Session Name: &nbsp;<b>' . substr($title, 0, 40) . '</b><br>
                            Doctor name&nbsp; : <b>' . substr($docname, 0, 40) . '</b><br><br>

                        </div>
                        <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id=' . $id . '" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"<font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>

                        </div>
                    </center>
            </div>
            </div>
            ';
        } elseif ($action == 'view') {
            $sqlmain = "select * from doctor where docid=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $name = $row["docname"];
            $email = $row["docemail"];
            $spe = $row["specialties"];

            $sqlmain = "select sname from specialties where id=?";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s", $spe);
            $stmt->execute();
            $spcil_res = $stmt->get_result();
            $spcil_array = $spcil_res->fetch_assoc();
            $spcil_name = $spcil_array["sname"];
            $nic = $row['docnic'];
            $tele = $row['doctel'];
            echo '
            <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            eDoc Web App<br>

                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">

                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>

                            <tr>

                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $name . '<br><br>
                                </td>

                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $email . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $nic . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                ' . $tele . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Specialties: </label>

                                </td>
                            </tr>
                            <tr>
                            <td class="label-td" colspan="2">
                            ' . $spcil_name . '<br><br>
                            </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>


                                </td>

                            </tr>


                        </table>
                        </div>
                    </center>
                    <br><br>
            </div>
            </div>
            ';
        }
    }

    ?>
    </div>

</body>

</html>

<script>
    function filterAppointments() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("dateInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("hospitalTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the filter
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[4]; // 4th column is Scheduled Date in your table

            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>