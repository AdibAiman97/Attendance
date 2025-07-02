<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

date_default_timezone_set('Asia/Kuala_Lumpur');

$dateTaken = date("d-m-Y");

if (isset($_POST['download'])) {
    $classId = $_POST['classId'];
    $dateType = $_POST['dateType'];
    $sessionType = $_POST['sessionType'];
    $query = "";

    if ($dateType == "1") { // Single Date
        $singleDate = $_POST['singleDate'];
        $query = "SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblattendance.timeTaken, tblclass.className,
                  tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                  tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                  FROM tblattendance
                  INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                  INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                  INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                  INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                  INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                  WHERE tblattendance.dateTimeTaken = '$singleDate'
                  AND tblattendance.classId = '$classId'
                  AND tblattendance.sessionType = '$sessionType'";
    } elseif ($dateType == "2") { // Date Range
        $fromDate = $_POST['fromDate'];
        $toDate = $_POST['toDate'];
        $query = "SELECT DISTINCT tblattendance.Id, tblattendance.status, tblattendance.dateTimeTaken, tblattendance.timeTaken, tblclass.className,
                  tblclassarms.classArmName, tblsessionterm.sessionName, tblsessionterm.termId, tblterm.termName,
                  tblstudents.firstName, tblstudents.lastName, tblstudents.otherName, tblstudents.admissionNumber
                  FROM tblattendance
                  INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
                  INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
                  INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
                  INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
                  INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
                  WHERE tblattendance.dateTimeTaken BETWEEN '$fromDate' AND '$toDate'
                  AND tblattendance.classId = '$classId'
                  AND tblattendance.sessionType = '$sessionType'";
    }

    $result = $conn->query($query);
    $filename = "Attendance_Report  " . "(".date("d-m-Y") .")". ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    echo "No\tFirst Name\tLast Name\tPhone Number\tID Student\tClass\tSector Name\tSessionType\tSession\tSemester\tStatus\tDate\tTime\n";

    $cnt = 1;
    $presentCount = 0;
    $totalCount = $result->num_rows;

    while ($row = $result->fetch_assoc()) {
        $status = $row['status'] == '1' ? 'Present' : 'Absent';
        if ($row['status'] == '1') {
            $presentCount++;
        }
        echo "$cnt\t{$row['firstName']}\t{$row['lastName']}\t{$row['otherName']}\t{$row['admissionNumber']}\t{$row['className']}\t{$row['classArmName']}\t{$sessionType}\t{$row['sessionName']}\t{$row['termName']}\t$status\t{$row['dateTimeTaken']}\t{$row['timeTaken']}\n";
        $cnt++;
    }

    $attendancePercentage = ($totalCount > 0) ? ($presentCount / $totalCount) * 100 : 0;
    echo "\n\nTotal Present\t$presentCount\n";
    echo "Attendance Percentage\t" . round($attendancePercentage, 2) . "%\n";
    exit;
}
?>