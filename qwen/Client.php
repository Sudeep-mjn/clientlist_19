<?php
class Client {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "nist19_admin");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Search client by name and code (must match same row)
    public function searchClient($name, $code) {
        $name = $this->conn->real_escape_string($name);
        $code = $this->conn->real_escape_string($code);

        $sql = "SELECT * FROM clientlist WHERE ClientName = '$name' AND ClientCode = '$code'";
        $result = $this->conn->query($sql);

        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    // Fetch client by SN
    public function getClientBySN($sn) {
        $sn = (int)$sn;
        $sql = "SELECT * FROM clientlist WHERE SN = $sn";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    // Save updated data to updateclient table
    public function saveUpdatedClient($data) {
        // Escape all values
        foreach ($data as $key => $value) {
            $data[$key] = $this->conn->real_escape_string($value);
        }

        $sql = "INSERT INTO updateclient 
            (ClientName, ClientCode, ClientType, CreatedOn, `DOB/DOI`, PermanentAddress, TemproaryAddress, 
             Landline, MobileNo, Citizenship, Pan, IssueDate, PlaceOfIssue, ClientRiskCategory, 
             BranchCode, Email, BOID, BankName, BankBranch, BankAccNo, BankAccName) 
        VALUES 
            ('{$data['ClientName']}', '{$data['ClientCode']}', '{$data['ClientType']}', '{$data['CreatedOn']}', 
             '{$data['DOB/DOI']}', '{$data['PermanentAddress']}', '{$data['TemproaryAddress']}', 
             '{$data['Landline']}', '{$data['MobileNo']}', '{$data['Citizenship']}', '{$data['Pan']}', 
             '{$data['IssueDate']}', '{$data['PlaceOfIssue']}', '{$data['ClientRiskCategory']}', 
             '{$data['BranchCode']}', '{$data['Email']}', '{$data['BOID']}', '{$data['BankName']}', 
             '{$data['BankBranch']}', '{$data['BankAccNo']}', '{$data['BankAccName']}')";

        return $this->conn->query($sql);
    }

    public function close() {
        $this->conn->close();
    }
}
?>