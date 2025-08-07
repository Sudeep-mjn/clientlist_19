<?php
require_once 'Client.php';
session_start();

// Optional: Validate session
if (!isset($_SESSION['allowed_edit_sn'])) {
    die("Access denied. No active session.");
}

$sn = (int)($_POST['SN'] ?? 0);
if ($sn != $_SESSION['allowed_edit_sn']) {
    die("Invalid request.");
}
if ($_POST) {
    $sn = isset($_POST['SN']) ? (int)$_POST['SN'] : 0;
    if ($sn <= 0) {
        die("Invalid client record.");
    }

    $clientObj = new Client();
    $original = $clientObj->getClientBySN($sn);
    if (!$original) {
        $clientObj->close();
        die("Original data not found.");
    }

    $submitted = $_POST;
    unset($submitted['SN']);

    $hasChanges = false;
    foreach ($submitted as $key => $value) {
        $orig = trim((string)($original[$key] ?? ''));
        $new = trim((string)($value ?? ''));
        if ($orig !== $new) {
            $hasChanges = true;
            break;
        }
    }

    if (!$hasChanges) {
        echo "<script>alert('No changes to save.'); window.location.href='edit.php?sn=$sn';</script>";
        $clientObj->close();
        exit();
    }

    $success = $clientObj->saveUpdatedClient($_POST);
    $clientObj->close();

    if ($success) {
        echo "<script>alert('Changes saved successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Save failed. Please try again.'); window.location.href='edit.php?sn=$sn';</script>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>