<?php
class Client {
    protected $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "nist19_admin");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function searchClient($name, $code) {
        $name = $this->conn->real_escape_string($name);
        $code = $this->conn->real_escape_string($code);
        $sql = "SELECT * FROM clientlist WHERE ClientName = '$name' AND ClientCode = '$code'";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    public function getClientBySN($sn) {
        $sn = (int)$sn;
        $sql = "SELECT * FROM clientlist WHERE SN = $sn";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0 ? $result->fetch_assoc() : false;
    }

    public function saveUpdatedClient($data) {
        foreach ($data as $key => $value) {
            $data[$key] = $this->conn->real_escape_string($value);
        }

        $sql = "INSERT INTO updateclient 
            (ClientName, ClientCode, ClientType, CreatedOn, `DOB/DOI`, PermanentAddress, TemproaryAddress, 
             Landline, MobileNo, Citizenship, Pan, IssueDate, PlaceOfIssue, ClientRiskCategory, 
             BranchCode, Email, BOID, BankName, BankBranch, BankAccNo, BankAccName, nid, BankAccType) 
        VALUES 
            ('{$data['ClientName']}', '{$data['ClientCode']}', '{$data['ClientType']}', '{$data['CreatedOn']}', 
             '{$data['DOB/DOI']}', '{$data['PermanentAddress']}', '{$data['TemproaryAddress']}', 
             '{$data['Landline']}', '{$data['MobileNo']}', '{$data['Citizenship']}', '{$data['Pan']}', 
             '{$data['IssueDate']}', '{$data['PlaceOfIssue']}', '{$data['ClientRiskCategory']}', 
             '{$data['BranchCode']}', '{$data['Email']}', '{$data['BOID']}', '{$data['BankName']}', 
             '{$data['BankBranch']}', '{$data['BankAccNo']}', '{$data['BankAccName']}','{$data['nid']}', '{$data['BankAccType']}')";

        return $this->conn->query($sql);
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>