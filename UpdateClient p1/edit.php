<?php
require_once 'Client.php';
session_start();

// ✅ Must have session permission
if (!isset($_SESSION['allowed_edit_sn'])) {
    die("<h3>Access Denied</h3>
         <p>You must search for a client first.</p>
         <a href='index.php'>&larr; Back to Search</a>");
}

$sn = (int)$_SESSION['allowed_edit_sn'];
$clientObj = new Client();
$client = $clientObj->getClientBySN($sn);
$clientObj->close();

if (!$client) {
    // Optional: Clear session if invalid
    unset($_SESSION['allowed_edit_sn']);
    die("Client not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Client Information</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            min-width: 250px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #34495e;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #fdfdfd;
            outline: none;
        }
        input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52,152,219,0.2);
        }
        /* Green highlight when changed */
        input.changed {
            background-color: #d5f5e3 !important;
            border-color: #27ae60 !important;
        }
        /* Read-only fields */
        input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }
        .form-actions {
            text-align: right;
            margin-top: 20px;
        }
        button {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover:not(:disabled) {
            background-color: #219a52;
        }
        button:disabled {
            background-color: #95a5a6;
            opacity: 0.6;
            cursor: not-allowed;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .form-row {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Client Information</h1>

        <form action="save.php" method="POST" id="editForm">
            <!-- Hidden SN (still needed for save.php) -->
            <input type="hidden" name="SN" value="<?= $client['SN'] ?>">

            <!-- Read-only fields -->
            <div class="form-row">
                <div class="form-group">
                    <label>Client Name</label>
                    <input type="text" name="ClientName" value="<?= htmlspecialchars($client['ClientName']) ?>" readonly class="change-detect">
                </div>
                <div class="form-group">
                    <label>Client Code</label>
                    <input type="text" name="ClientCode" value="<?= htmlspecialchars($client['ClientCode']) ?>" readonly class="change-detect">
                </div>
            </div>

            <!-- <div class="form-row">
                <div class="form-group">
                    <label>Client Type</label>
                    <input type="text" name="ClientType" value="<?= htmlspecialchars($client['ClientType']) ?>" readonly class="change-detect">
                </div>
                <div class="form-group">
                    <label>Created On</label>
                    <input type="text" name="CreatedOn" value="<?= htmlspecialchars($client['CreatedOn']) ?>" readonly class="change-detect">
                </div>
            </div> -->

            <!-- Editable fields -->
            <div class="form-group">
                <!-- <label>DOB/DOI</label>
                <input type="text" name="DOB/DOI" value="<?= htmlspecialchars($client['DOB/DOI']) ?>" class="change-detect">
            </div>

            <div class="form-group">
                <label>Permanent Address</label>
                <input type="text" name="PermanentAddress" value="<?= htmlspecialchars($client['PermanentAddress']) ?>" class="change-detect">
            </div> -->

            <div class="form-group">
                <label>Current Address</label>
                <input type="text" name="TemproaryAddress" value="<?= htmlspecialchars($client['TemproaryAddress']) ?>" class="change-detect">
            </div>

            <!-- <div class="form-row">
                <div class="form-group">
                    <label>Landline</label>
                    <input type="text" name="Landline" value="<?= htmlspecialchars($client['Landline']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>Mobile No</label>
                    <input type="text" name="MobileNo" value="<?= htmlspecialchars($client['MobileNo']) ?>" class="change-detect">
                </div>
            </div> -->

            <div class="form-row">
                <!-- <div class="form-group">
                    <label>Citizenship</label>
                    <input type="text" name="Citizenship" value="<?= htmlspecialchars($client['Citizenship']) ?>" class="change-detect">
                </div> -->
                <div class="form-group">
                    <label>NID</label>
                    <input type="text" name="nid" value="<?= htmlspecialchars($client['nid']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>PAN</label>
                    <input type="text" name="Pan" value="<?= htmlspecialchars($client['Pan']) ?>" class="change-detect">
                </div>
            </div>

            <!-- <div class="form-row">
                <div class="form-group">
                    <label>Issue Date</label>
                    <input type="text" name="IssueDate" value="<?= htmlspecialchars($client['IssueDate']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>Place of Issue</label>
                    <input type="text" name="PlaceOfIssue" value="<?= htmlspecialchars($client['PlaceOfIssue']) ?>" class="change-detect">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Client Risk Category</label>
                    <input type="text" name="ClientRiskCategory" value="<?= htmlspecialchars($client['ClientRiskCategory']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>Branch Code</label>
                    <input type="text" name="BranchCode" value="<?= htmlspecialchars($client['BranchCode']) ?>" class="change-detect">
                </div>
            </div> -->

            <!-- <div class="form-group">
                <label>Email</label>
                <input type="email" name="Email" value="<?= htmlspecialchars($client['Email']) ?>" class="change-detect">
            </div> -->

            <div class="form-row">
                <!-- <div class="form-group">
                    <label>BOID</label>
                    <input type="text" name="BOID" value="<?= htmlspecialchars($client['BOID']) ?>" class="change-detect">
                </div> -->
                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="BankName" value="<?= htmlspecialchars($client['BankName']) ?>" class="change-detect">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Bank Branch</label>
                    <input type="text" name="BankBranch" value="<?= htmlspecialchars($client['BankBranch']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>Bank Account No</label>
                    <input type="text" name="BankAccNo" value="<?= htmlspecialchars($client['BankAccNo']) ?>" class="change-detect">
                </div>
                <div class="form-group">
                    <label>Bank Account Type</label>
                    <input type="text" name="BankAccType" value="<?= htmlspecialchars($client['BankAccType']) ?>" class="change-detect">
                </div>
            </div>

            <div class="form-group">
                <label>Bank Account Name</label>
                <input type="text" name="BankAccName" value="<?= htmlspecialchars($client['BankAccName']) ?>" class="change-detect">
            </div>

            <div class="form-actions">
                <button type="submit" id="saveBtn" disabled>Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('editForm');
            const saveBtn = document.getElementById('saveBtn');
            const inputs = document.querySelectorAll('.change-detect');

            // Store original values
            const originals = {};
            inputs.forEach(input => {
                originals[input.name] = input.value;
            });

            // Track changes
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    if (this.value !== originals[this.name]) {
                        this.classList.add('changed');
                    } else {
                        this.classList.remove('changed');
                    }

                    // Check if any field changed
                    const hasChanges = Array.from(inputs).some(input => {
                        return input.value !== originals[input.name];
                    });

                    saveBtn.disabled = !hasChanges;
                });
            });

            // Disable save button initially
            saveBtn.disabled = true;
        });
    </script>
</body>
</html>