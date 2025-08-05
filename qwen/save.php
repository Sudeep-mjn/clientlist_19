<?php
require_once 'Client.php';

if ($_POST) {
    $data = $_POST;

    // Remove SN if present (not needed for insert)
    unset($data['SN']);

    $clientObj = new Client();
    $success = $clientObj->saveUpdatedClient($data);
    $clientObj->close();

    if ($success) {
        echo "<script>alert('Client updated successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Failed to update client.'); window.location.href='edit.php?sn=" . $_POST['SN'] . "';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>