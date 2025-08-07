<?php
require_once 'Client.php';
session_start();

// if (!isset($_SESSION['allowed_edit_sn']) || $_SESSION['allowed_edit_sn'] != (int)$_POST['SN']) {
//     die("Access denied.");
// }

if ($_POST) {
    $clientName = trim($_POST['ClientName']);
    $clientCode = trim($_POST['ClientCode']);

    $clientObj = new Client();
    $result = $clientObj->searchClient($clientName, $clientCode);
    $clientObj->close();

    if ($result) {
        // ✅ Store SN in session
        $_SESSION['allowed_edit_sn'] = $result['SN'];

        // ✅ Redirect without SN in URL
        header("Location: edit.php");
        exit();
    } else {
        echo "<script>alert('No matching client found.'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>