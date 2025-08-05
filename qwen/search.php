<?php
require_once 'Client.php';

if ($_POST) {
    $clientName = $_POST['ClientName'];
    $clientCode = $_POST['ClientCode'];

    $clientObj = new Client();
    $result = $clientObj->searchClient($clientName, $clientCode);
    $clientObj->close();

    if ($result) {
        // Redirect to edit with SN
        header("Location: edit.php?sn=" . $result['SN']);
        exit();
    } else {
        echo "<script>alert('No matching client found.'); window.location.href='index.php';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>