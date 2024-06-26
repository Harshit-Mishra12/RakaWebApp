<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/animations.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/admin.css">
    <title>Hospital Information</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }

        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .input-icon {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon-addon {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    pointer-events: none; /* Ensure the icon doesn't interfere with dropdown selection */
}

.input-icon img {
    width: 10px; /* Adjust as needed */
    height: 5px; /* Adjust as needed */
}

.table-text-align{

    text-align: left
}

    </style>
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        if (($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
            $username = isset($_SESSION["username"]) ? $_SESSION["username"] : "User";
        }
    } else {
        header("location: ../login.php");
    }

    include("../connection.php");

    // Static data for hospitals
    $hospitals = [
        ["name" => "City Hospital", "address" => "123 Main St, Cityville", "contact" => "0123456789"],
        ["name" => "Town Health Clinic", "address" => "456 Elm St, Townsville", "contact" => "9876543210"],
        ["name" => "Metro Medical Center", "address" => "789 Oak St, Metropolis", "contact" => "5678901234"]
    ];

    $hospital_count = count($hospitals);
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
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
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
                        <a href="index.php" class="non-style-link-menu">
                            <div>
                                <p class="menu-text">Home</p>
                        </a>
        </div>
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
        <td class="menu-btn menu-icon-session">
            <a href="appointment.php" class="non-style-link-menu">
                <div>
                <p class="menu-text">My Bookings</p>
                </div>
            </a>
        </td>
    </tr>

    <tr class="menu-row">
        <td class="menu-btn menu-icon-appoinment menu-active menu-icon-appoinment-active">
            <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active">
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
                    <p style="font-size: 23px; padding-left:12px; font-weight: 600;">Hospital Information</p>
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
                                    <input type="text" class="input-text filter-container-input" id="searchInput" placeholder="Search Hospital Name" onkeyup="searchFunction()">
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
                                        <th class="table-headin table-text-align ">Hospital Name</th>
                                        <th class="table-headin table-text-align">Address</th>
                                        <th class="table-headin table-text-align">Contact</th>
                                        <th class="table-headin table-text-align">Book Appointment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($hospitals as $hospital) : ?>
                                        <tr>
                                            <td class="table-text-align"><?php echo $hospital["name"]; ?></td>
                                            <td class="table-text-align"><?php echo $hospital["address"]; ?></td>
                                            <td class="table-text-align"><?php echo $hospital["contact"]; ?></td>
                                            <td class="table-text-align"><button class="book-appointment-btn login-btn btn-primary-soft btn"><a href="?action=drop&id=none&error=0">Book Appointment</a< /button>
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
    if ($action == 'drop') {
        $nameget = $_GET["name"];

        // Static array of services with associated slots (example)
        $services = [
            "Service 1" => ["09:00 AM", "10:00 AM", "11:00 AM"],
            "Service 2" => ["01:00 PM", "02:00 PM", "03:00 PM"],
            "Service 3" => ["04:00 PM", "05:00 PM", "06:00 PM"]
        ];

        ?>
        <div id="popup1" class="overlay">
            <div class="popup">
                <h2>Select Your Slot</h2>
                <a class="close" href="new-appointment.php">&times;</a>

                <form action="book_appointment.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="form-group">
                        <label for="serviceSelect">Select Service:</label>
                        <div class="input-icon" style="margin-top: 5px!important;">
                            <span class="input-icon-addon">
                                <img src="../img/down_arrow.png" alt="Dropdown Icon">
                            </span>
                            <select id="serviceSelect" name="service" class="input-text" style="margin-top: 5px!important;">
                            <option value="">Select Service</option>
                            <?php
                            foreach ($services as $service => $slots) {
                                echo '<option value="' . $service . '">' . $service . '</option>';
                            }
                            ?>
                        </select>
                        </div>

                    </div>
                    <br></br>
                    <div class="form-group" >
                        <label for="slotSelect">Select Time Slot:</label>
                        <div class="input-icon" style="margin-top: 5px!important;">
                            <span class="input-icon-addon">
                                <img src="../img/down_arrow.png" alt="Dropdown Icon">
                            </span>
                            <select id="slotSelect" name="slot" class="input-text">
                                <option value="">Select Slot</option>
                            </select>
                        </div>
                    </div>
                    <div class="button-group" style="margin-top: 20px!important;">

                        <a  href="?action=product-purchase&id=none&error=0" class="btn-primary btn" style="margin-right:5px">
                            <span class="btn-text">Confirm</span>
                        </a>
                        <a href="doctors.php" class="btn-primary btn">
                            <span class="btn-text">Cancel</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
    elseif ($action == 'product-purchase') {
        $service = $_POST["service"];
        $slot = $_POST["slot"];
        ?>

        <div id="purchaseConfirmation" class="overlay">
            <div class="popup">
                <center>
                    <h2>Purchase Product Alongside Booking?</h2>
                    <a class="close" href="new-appointment.php">&times;</a>
                    <div class="content">
                        Would you like to purchase a product alongside your booking?
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <a href="purchase.php?id=<?php echo $id; ?>&service=<?php echo $service; ?>&slot=<?php echo $slot; ?>" class="btn-primary btn" style="margin: 10px; padding: 10px;">Yes</a>
                        <a href="new-appointment.php" class="btn-primary btn" style="margin: 10px; padding: 10px;">No</a>
                    </div>
                </center>
            </div>
        </div>
        <?php
    }
}
?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("serviceSelect").addEventListener("change", function() {
                var service = this.value;
                var slotSelect = document.getElementById("slotSelect");
                slotSelect.innerHTML = ""; // Clear previous options

                if (service !== "") {
                    var slots = <?php echo json_encode($services); ?>[service];
                    slots.forEach(function(slot) {
                        var option = document.createElement("option");
                        option.text = slot;
                        slotSelect.add(option);
                    });
                }
            });
        });
    </script>

    <script>
        function searchFunction() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("hospitalTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
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


</body>

</html>