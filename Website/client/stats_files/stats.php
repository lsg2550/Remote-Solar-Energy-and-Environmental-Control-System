<?php
    //Require
    require("../../index_files/sessionstart.php");
    require("../../index_files/sessioncheck.php");

    function generateRPISelect() {
        require_once("../../index_files/connect.php");

        //Database Queries
        $currentUser = $_SESSION['username']; //Get Current User Name
        $sqlRPis = "SELECT rpiID FROM rpi WHERE owner='{$currentUser}';"; // Select all RPis belonging to the current user

        //Execute Queries
        $resultRPis = mysqli_query($conn, $sqlRPis);

        //Store RPis into an array of RPis
        $arrayRPis = array(); 
        if(mysqli_num_rows($resultRPis) > 0) {
            while($row = mysqli_fetch_assoc($resultRPis)) {
                $arrayRPis[] = $row['rpiID'];
            }
        }

        # Create the HTML dropdown menu
        echo "<select id='rpi-select' name='rpi_select' required>";
        for ($i=0; $i < count($arrayRPis); $i++) { 
            echo "<option value='{$arrayRPis[$i]}'>{$arrayRPis[$i]}</option>";
        }
        echo "</select>";
    }
?>

<html>
<head>
    <title>Statistics Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
    <script src="createchart.js"></script>
    <link rel="stylesheet" type="text/css" href="stats.css">
    <link rel="stylesheet" type="text/css" href="../client.css">
</head>

<body>
    <h1 class="title">Remote Site - Mobile Solar Energy & Environmental Control System</h1>

    <div class="formdiv">
        <form action="../client.php" method="post">
            <input type="submit" value="Client Page">
        </form>
        <form action="../../index_files/logout.php" method="post">
            <input type="submit" value="Log Out">
        </form>
    </div>

    <div class="formdiv" id="data-preview-select">
        <form id="data-preview-select" method="post">
            <!-- Select Date -->
            <label for="date-start-select">Start Date:</label>
            <input type="date" class="date-range" id="date-start-select" name="date_start" >
            <label for="date-end-select">End Date:</label>
            <input type="date" class="date-range" id="date-end-select" name="date_end" >

            <!-- Select Time -->
            <label for="time-start-select">Start Time:</label>
            <input type="time" class="time-range" id="time-start-select" name="time_start" >
            <label for="time-end-select">End Time:</label>
            <input type="time" class="time-range" id="time-end-select" name="time_end" >

            <!-- Select Time Interval -->
            <label for="time-interval">Time Interval:</label>
            <select id="time-interval" name="time_interval" >
                <option value="30">30 Minutes</option>
                <option value="60">1 Hour</option>
                <option value="90">1 Hour 30 Minutes</option>
                <option value="120">2 Hour</option>
                <option value="150">2 Hour 30 Minutes</option>
                <option value="180">3 Hour</option>
            </select>
            <br />

            <!-- Select RPi -->
            <label for="rpi-select">RPi:</label>
            <?php generateRPISelect(); ?>

            <!-- Select Vital -->
            <label>Vitals:</label>
            <label>Battery</label><input type="checkbox" name="vital1" value="battery" checked> <!-- Voltage/Current -->
            <label>PV</label><input type="checkbox" name="vital2" value="solar" checked> <!-- Voltage/Current -->
            <label>Temperature</label><input type="checkbox" name="vital3" value="temperature" checked> <!-- Inner/Outer -->
            <label>Humidity</label><input type="checkbox" name="vital4" value="humidity" checked> <!-- Inner/Outer -->
            <label>Exhaust</label><input type="checkbox" name="vital5" value="exhaust" checked> <!-- Single -->

            <!-- Select Chart/CSV Configuration -->
            <label>Settings:</label>
            <label>Interpolate Missing Data</label><input type="checkbox" name="interpolate_data" value="interpolate" checked> 
            <br />

            <!-- Submit -->
            <button input="submit" class="formsubmit" id="createchart" name="formaction" value="chart">Create Chart</button>
            <button input="submit" class="formsubmit" id="downloadcsv" name="formaction" value="csv">Download CSV</button>
        </form>
    </div>

    <div class="charts">
    </div>
</body>
</html>