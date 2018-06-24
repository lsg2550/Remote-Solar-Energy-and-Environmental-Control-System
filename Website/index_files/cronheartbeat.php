<?php
    //Require
    require("connect.php");

    //Date Difference Format
    $diffFormat = "%a";

    //Get Timestamp
    date_default_timezone_set('UTC');
    $currentTimestamp = new DateTime(date("Y-m-d H:i:s", time()));

    //Get List of RPis
    $sqlGetListOfRPis = "SELECT rpiID from rpi ORDER BY rpiID ASC;";
    $resultGetListOfRPis = mysqli_query($conn, $sqlGetListOfRPis);

    //SQL get most recent timestamp
    while($result = mysqli_fetch_assoc($resultGetListOfRPis)) {
        //Get the most recent timestamp of the current RPi
        $sqlGetMostRecentTimestamp = "SELECT TS FROM log WHERE RPID='{$result['rpiID']}' AND TYP='ST' ORDER BY TS DESC LIMIT 1;";
        $resultGetMostRecentTimestamp = mysqli_query($conn, $sqlGetMostRecentTimestamp);
        $lastUpdateByRPi = mysqli_fetch_assoc($resultGetMostRecentTimestamp)['TS'];
        if($lastUpdateByRPi == "") { continue; } //If there are no updates by the current RPi, move on to the next one (if any)
        echo "Last Update by RPi '{$result['rpiID']}' was at '{$lastUpdateByRPi}'...<br>";

        //Calculate difference between last update and now.
        $lastUpdateByRPi_DateObject = new DateTime($lastUpdateByRPi);
        $dateDifference = $lastUpdateByRPi_DateObject->diff($currentTimestamp);
        $dateDifferenceFormatted = $dateDifference->format($diffFormat);

        //If it has been more than a week since the RPi has contacted the server, send an email to the user!
        if($dateDifference->days > 7) { echo "It has been 7 days since RPi '{$result['rpiID']}' has made contact with the server!"; } 
        else { echo "It has been less than 7 days since RPi '{$result['rpiID']}' has made contact with the server!"; }
    }
?>