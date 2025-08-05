<?php
require_once 'Client.php';

if (!isset($_GET['sn']) || !is_numeric($_GET['sn'])) {
    die("Invalid request.");
}

$sn = (int)$_GET['sn'];
$clientObj = new Client();
$client = $clientObj->getClientBySN($sn);
$clientObj->close();

if (!$client) {
    die("Client not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto p-6 max-w-4xl">
    <h1 class="text-2xl font-bold mb-6 text-center text-blue-700">Edit Client Information</h1>

    <form action="save.php" method="POST" id="editForm" class="bg-white p-6 rounded-lg shadow-md">

        <!-- Hidden SN -->
        <input type="hidden" name="SN" value="<?= $client['SN'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Client Name</label>
                <input type="text" name="ClientName" value="<?= htmlspecialchars($client['ClientName']) ?>" readonly
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Client Code</label>
                <input type="text" name="ClientCode" value="<?= htmlspecialchars($client['ClientCode']) ?>"readonly
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Client Type</label>
                <input type="text" name="ClientType" value="<?= htmlspecialchars($client['ClientType']) ?>"readonly
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Created On</label>
                <input type="text" name="CreatedOn" value="<?= htmlspecialchars($client['CreatedOn']) ?>"readonly
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">DOB/DOI</label>
            <input type="text" name="DOB/DOI" value="<?= htmlspecialchars($client['DOB/DOI']) ?>"readonly
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Permanent Address</label>
            <input type="text" name="PermanentAddress" value="<?= htmlspecialchars($client['PermanentAddress']) ?>"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Temporary Address</label>
            <input type="text" name="TemproaryAddress" value="<?= htmlspecialchars($client['TemproaryAddress']) ?>"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Landline</label>
                <input type="text" name="Landline" value="<?= htmlspecialchars($client['Landline']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Mobile No</label>
                <input type="text" name="MobileNo" value="<?= htmlspecialchars($client['MobileNo']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Citizenship</label>
                <input type="text" name="Citizenship" value="<?= htmlspecialchars($client['Citizenship']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">PAN</label>
                <input type="text" name="Pan" value="<?= htmlspecialchars($client['Pan']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Issue Date</label>
                <input type="text" name="IssueDate" value="<?= htmlspecialchars($client['IssueDate']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Place of Issue</label>
                <input type="text" name="PlaceOfIssue" value="<?= htmlspecialchars($client['PlaceOfIssue']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Client Risk Category</label>
                <input type="text" name="ClientRiskCategory" value="<?= htmlspecialchars($client['ClientRiskCategory']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Branch Code</label>
                <input type="text" name="BranchCode" value="<?= htmlspecialchars($client['BranchCode']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
            <input type="email" name="Email" value="<?= htmlspecialchars($client['Email']) ?>"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">BOID</label>
                <input type="text" name="BOID" value="<?= htmlspecialchars($client['BOID']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Bank Name</label>
                <input type="text" name="BankName" value="<?= htmlspecialchars($client['BankName']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Bank Branch</label>
                <input type="text" name="BankBranch" value="<?= htmlspecialchars($client['BankBranch']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-medium mb-1">Bank Account No</label>
                <input type="text" name="BankAccNo" value="<?= htmlspecialchars($client['BankAccNo']) ?>"
                       class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-1">Bank Account Name</label>
            <input type="text" name="BankAccName" value="<?= htmlspecialchars($client['BankAccName']) ?>"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 change-detect"/>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded transition duration-200">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    // Highlight modified fields in green
    document.querySelectorAll('.change-detect').forEach(input => {
        const originalValue = input.value;

        input.addEventListener('input', function () {
            if (this.value !== originalValue) {
                this.classList.add('bg-green-100', 'border-green-500');
            } else {
                this.classList.remove('bg-green-100', 'border-green-500');
            }
        });
    });
</script>

</body>
</html>