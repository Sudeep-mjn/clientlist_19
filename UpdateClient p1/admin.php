<?php
require_once 'Client.php';

class Admin extends Client {
    public function __construct() {
        parent::__construct();
    }

    public function getAllUpdatedClients($search = '', $from_date = '', $to_date = '', $page = 1, $limit = 25) {
        $search = $this->conn->real_escape_string(trim($search));
        $from_date = $this->conn->real_escape_string(trim($from_date));
        $to_date = $this->conn->real_escape_string(trim($to_date));
        $offset = ($page - 1) * $limit;

        $sql = "SELECT 
                    u.SN as update_sn,
                    u.ClientName, u.ClientCode, u.ClientType, u.CreatedOn, u.`DOB/DOI`,
                    u.PermanentAddress, u.TemproaryAddress, u.Landline, u.MobileNo,
                    u.Citizenship, u.Pan, u.IssueDate, u.PlaceOfIssue, u.ClientRiskCategory,u.nid,
                    u.BranchCode, u.Email, u.BOID, u.BankName, u.BankBranch, u.BankAccNo, u.BankAccName, u.BankAccType,
                    u.is_verified, u.created_at, u.updated_by,
                    COALESCE(c.ClientName, 'N/A') as orig_ClientName,
                    COALESCE(c.ClientCode, 'N/A') as orig_ClientCode,
                    COALESCE(c.ClientType, 'N/A') as orig_ClientType,
                    COALESCE(c.CreatedOn, '0000-00-00') as orig_CreatedOn,
                    COALESCE(c.`DOB/DOI`, '0000-00-00') as orig_DOBDOI,
                    COALESCE(c.PermanentAddress, 'N/A') as orig_PermanentAddress,
                    COALESCE(c.TemproaryAddress, 'N/A') as orig_TemproaryAddress,
                    COALESCE(c.Landline, 'N/A') as orig_Landline,
                    COALESCE(c.MobileNo, 'N/A') as orig_MobileNo,
                    COALESCE(c.Citizenship, 'N/A') as orig_Citizenship,
                    COALESCE(c.Pan, 'N/A') as orig_Pan,
                    COALESCE(c.IssueDate, '0000-00-00') as orig_IssueDate,
                    COALESCE(c.PlaceOfIssue, 'N/A') as orig_PlaceOfIssue,
                    COALESCE(c.ClientRiskCategory, 'N/A') as orig_ClientRiskCategory,
                    COALESCE(c.BranchCode, 'N/A') as orig_BranchCode,
                    COALESCE(c.Email, 'N/A') as orig_Email,
                    COALESCE(c.BOID, 'N/A') as orig_BOID,
                    COALESCE(c.BankName, 'N/A') as orig_BankName,
                    COALESCE(c.BankBranch, 'N/A') as orig_BankBranch,
                    COALESCE(c.BankAccNo, 'N/A') as orig_BankAccNo,
                    COALESCE(c.BankAccName, 'N/A') as orig_BankAccName,
                    COALESCE(c.nid, 'N/A') as orig_nid,
                    COALESCE(c.BankAccType, 'N/A') as orig_BankAccType
                FROM updateclient u
                LEFT JOIN clientlist c 
                    ON TRIM(LOWER(u.ClientCode)) = TRIM(LOWER(c.ClientCode))
                    OR TRIM(LOWER(u.ClientName)) = TRIM(LOWER(c.ClientName))
                WHERE 1=1";

        if ($search) {
            $sql .= " AND (u.ClientName LIKE '%$search%' OR u.ClientCode LIKE '%$search%')";
        }
        if ($from_date) {
            $sql .= " AND u.created_at >= '$from_date'";
        }
        if ($to_date) {
            $sql .= " AND u.created_at <= '$to_date 23:59:59'";
        }

        $sql .= " ORDER BY u.created_at DESC LIMIT $limit OFFSET $offset";

        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getTotalCount($search = '', $from_date = '', $to_date = '') {
        $search = $this->conn->real_escape_string(trim($search));
        $from_date = $this->conn->real_escape_string(trim($from_date));
        $to_date = $this->conn->real_escape_string(trim($to_date));

        $sql = "SELECT COUNT(*) as total FROM updateclient u WHERE 1=1";
        if ($search) {
            $sql .= " AND (u.ClientName LIKE '%$search%' OR u.ClientCode LIKE '%$search%')";
        }
        if ($from_date) {
            $sql .= " AND u.created_at >= '$from_date'";
        }
        if ($to_date) {
            $sql .= " AND u.created_at <= '$to_date 23:59:59'";
        }

        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];
    }
}

// ✅ Handle AJAX Verification Only
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'verify') {
    ob_clean(); // Prevent any output

    $sn = (int)($_POST['sn'] ?? 0);
    $name = trim($_POST['verifier_name'] ?? '');

    if ($sn <= 0 || empty($name)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $admin = new Admin();
    $name = $admin->conn->real_escape_string($name);

    $sql = "UPDATE updateclient 
            SET is_verified = 1, updated_by = '$name' 
            WHERE SN = $sn";

    if ($admin->conn->query($sql)) {
        http_response_code(200);
        echo json_encode(['success' => true, 'name' => $name]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'DB Error: ' . $admin->conn->error]);
    }
    $admin->close();
    exit;
}

// Start HTML output
$search = $_GET['q'] ?? '';
$from_date = $_GET['from'] ?? '';
$to_date = $_GET['to'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 25;

$admin = new Admin();
$records = $admin->getAllUpdatedClients($search, $from_date, $to_date, $page, $limit);
$total = $admin->getTotalCount($search, $from_date, $to_date);
$totalPages = ceil($total / $limit);
$admin->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin: Change Audit Log</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .filters {
            background: white;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
        }
        .filters label {
            font-size: 14px;
            font-weight: 600;
            color: #34495e;
        }
        .filters input {
            padding: 8px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background-color: #ecf0f1;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            font-size: 12px;
            text-transform: uppercase;
            color: #2c3e50;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        .changed-field {
            /* background-color: #d5f5e3; */
            /* border: 1px solid #27ae60; */
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 12px;
            display: inline-block;
            margin: 2px 0;
        }
        .changed-field strong {
            font-weight: 600;
        }
        .changed-field .old {
            text-decoration: line-through;
            color: #e74c3c;
        }
        .changed-field .new {
            /* color: #27ae60; */
            font-weight: bold;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #7f8c8d;
        }
        .pagination a {
            padding: 6px 12px;
            background: #ecf0f1;
            border-radius: 4px;
            text-decoration: none;
            color: #2c3e50;
            border: 1px solid #bdc3c7;
        }
        .pagination a:hover {
            background: #bdc3c7;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .modal h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .modal input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }
        .modal button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .modal button:last-child {
            background: #27ae60;
            color: white;
        }
        .modal button:first-child {
            background: #95a5a6;
            color: white;
            margin-right: 10px;
        }
        .verified {
            color: #27ae60;
            font-weight: 600;
        }
        .not-verified {
            color: #95a5a6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin: Change Audit Log</h1>

        <!-- Search & Filters -->
        <div class="filters">
            <div>
                <label>Search Name/Code</label>
                <input type="text" id="searchInput" value="<?= htmlspecialchars($search) ?>" placeholder="Search...">
            </div>
            <div>
                <label>From Date</label>
                <input type="date" id="fromDate" value="<?= htmlspecialchars($from_date) ?>">
            </div>
            <div>
                <label>To Date</label>
                <input type="date" id="toDate" value="<?= htmlspecialchars($to_date) ?>">
            </div>
        </div>

        <!-- Data Table -->
        <table id="auditTable">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Client Info</th>
                    <th>Changes</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($records)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; color:#7f8c8d;">No records found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($records as $r): ?>
                        <tr data-name="<?= htmlspecialchars($r['ClientName']) ?>" data-code="<?= htmlspecialchars($r['ClientCode']) ?>">
                            <td><?= $r['update_sn'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($r['ClientName']) ?></strong><br>
                                <small>Code: <?= htmlspecialchars($r['ClientCode']) ?></small>
                            </td>
                            <td style="max-width:300px;">
                                <?php
                                $changes = [];
                                //field map to fill/show changes in the table
                                $fieldMap = [
                                    'ClientName' => 'Client Name',
                                    'ClientCode' => 'Client Code',
                                    // 'ClientType' => 'Client Type',
                                    // 'CreatedOn' => 'Created On',
                                    // 'DOB/DOI' => 'DOB/DOI',
                                    // 'PermanentAddress' => 'Permanent Address',
                                    'TemproaryAddress' => 'Temporary Address',
                                    // 'Landline' => 'Landline',
                                    // 'MobileNo' => 'Mobile No',
                                    // 'Citizenship' => 'Citizenship',
                                    'nid' => 'nid',
                                    'Pan' => 'PAN',
                                    // 'IssueDate' => 'Issue Date',
                                    // 'PlaceOfIssue' => 'Place of Issue',
                                    // 'ClientRiskCategory' => 'Risk Category',
                                    // 'BranchCode' => 'Branch Code',
                                    // 'Email' => 'Email',
                                    // 'BOID' => 'BOID',
                                    'BankName' => 'Bank Name',
                                    'BankBranch' => 'Bank Branch',
                                    'BankAccNo' => 'Bank Acc No',
                                    'BankAccName' => 'Bank Acc Name',
                                    'BankAccType' => 'Bank Acc Type'
                                ];

                                foreach ($fieldMap as $dbField => $label) {
                                    $original = trim((string)($r["orig_$dbField"] ?? 'N/A'));
                                    $current = trim((string)($r[$dbField] ?? 'N/A'));
                                    $displayOrig = in_array($original, ['0000-00-00', '']) ? 'N/A' : htmlspecialchars($original);
                                    $displayCurr = in_array($current, ['0000-00-00', '']) ? 'N/A' : htmlspecialchars($current);
                                    if ($displayOrig === $displayCurr) continue;

                                    $changes[] = "
                                        <div class='changed-field'>
                                            <strong>$label:</strong> 
                                            <span class='old'>$displayOrig</span> → 
                                            <span class='new'>$displayCurr</span>
                                        </div>";
                                }

                                echo empty($changes) 
                                    ? "<span class='not-verified'>No changes</span>" 
                                    : implode('', $changes);
                                ?>
                            </td>
                            <td><?= htmlspecialchars($r['created_at']) ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <div>Page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> records)</div>
                <div>
                    <?php if ($page > 1): ?>
                        <a href="?q=<?= urlencode($search) ?>&from=<?= urlencode($from_date) ?>&to=<?= urlencode($to_date) ?>&page=<?= $page - 1 ?>">Previous</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?q=<?= urlencode($search) ?>&from=<?= urlencode($from_date) ?>&to=<?= urlencode($to_date) ?>&page=<?= $page + 1 ?>">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Verification Modal -->
    <div id="verifyModal" class="modal">
        <div class="modal-content">
            <h3>Verify Change</h3>
            <p>Please enter your name to verify:</p>
            <input type="text" id="verifierName" placeholder="Your Name">
            <div>
                <button type="button" onclick="closeVerifyModal()">Cancel</button>
                <button type="button" onclick="submitVerification()">Submit</button>
            </div>
        </div>
    </div>

    <script>
        let currentSN = null;

        function openVerifyModal(sn) {
            currentSN = sn;
            document.getElementById('verifyModal').style.display = 'flex';
            document.getElementById('verifierName').value = '';
            document.getElementById('verifierName').focus();
        }

        function closeVerifyModal() {
            document.getElementById('verifyModal').style.display = 'none';
            currentSN = null;
        }

        function submitVerification() {
            const name = document.getElementById('verifierName').value.trim();
            if (!name) {
                alert('Please enter your name.');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'verify');
            formData.append('sn', currentSN);
            formData.append('verifier_name', name);

            fetch('admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(`Verified successfully by ${data.name}`);
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Unknown'));
                }
            })
            .catch(err => {
                alert('Network error: ' + err.message);
            });
        }

        // Search & Filter
        function applyFilters() {
            const q = encodeURIComponent(document.getElementById('searchInput').value);
            const from = document.getElementById('fromDate').value;
            const to = document.getElementById('toDate').value;
            const url = `?q=${q}&from=${from}&to=${to}`;
            window.location.href = url;
        }

        // Live search
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('fromDate').addEventListener('change', applyFilters);
        document.getElementById('toDate').addEventListener('change', applyFilters);

        // Auto-fill on load
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const q = urlParams.get('q');
            const from = urlParams.get('from');
            const to = urlParams.get('to');
            if (q) document.getElementById('searchInput').value = q;
            if (from) document.getElementById('fromDate').value = from;
            if (to) document.getElementById('toDate').value = to;
        };
    </script>
</body>
</html>