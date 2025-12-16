<?php
/*
 * AssistanceModel Class
 * Handles database operations for both Incoming and Distributed assistance tables.
 */
require_once 'config/Database.php';

class AssistanceModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Retrieve all records from tbantuanmasuk
    public function getIncomingAssistance() {
        $query = "SELECT * FROM tbantuanmasuk ORDER BY tanggalmasuk DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Retrieve all records from tbantuansalur
    public function getDistributedAssistance() {
        $query = "SELECT * FROM tbantuansalur ORDER BY tanggalmasuk DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Insert new record into tbantuanmasuk
    public function addAssistance($data) {
        $query = "INSERT INTO tbantuanmasuk (id, donatur, isibantuan, tanggalmasuk, nilai, daerahsalur, status) 
                  VALUES (:id, :donatur, :isibantuan, :tanggalmasuk, :nilai, :daerahsalur, 'masuk')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":id", $data['id']);
        $stmt->bindParam(":donatur", $data['donatur']);
        $stmt->bindParam(":isibantuan", $data['isibantuan']);
        $stmt->bindParam(":tanggalmasuk", $data['tanggalmasuk']);
        $stmt->bindParam(":nilai", $data['nilai']);
        $stmt->bindParam(":daerahsalur", $data['daerahsalur']);
        
        return $stmt->execute();
    }

    // Update 'nilai' and 'status' in tbantuanmasuk
    public function updateIncoming($id, $nilai, $status) {
        $query = "UPDATE tbantuanmasuk SET nilai = :nilai, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nilai", $nilai);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Update 'status' only in tbantuansalur (e.g., changing to 'hilang')
    public function updateDistributed($id, $status) {
        $query = "UPDATE tbantuansalur SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Delete record from tbantuanmasuk
    public function deleteIncoming($id) {
        $query = "DELETE FROM tbantuanmasuk WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Transaction: Move record from tbantuanmasuk to tbantuansalur
    public function distributeAssistance($id) {
        try {
            $this->conn->beginTransaction();

            // 1. Fetch source data
            $sqlSelect = "SELECT * FROM tbantuanmasuk WHERE id = :id";
            $stmtSelect = $this->conn->prepare($sqlSelect);
            $stmtSelect->bindParam(":id", $id);
            $stmtSelect->execute();
            $row = $stmtSelect->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // 2. Insert into destination with status 'tersalur'
                $sqlInsert = "INSERT INTO tbantuansalur (id, donatur, isibantuan, tanggalmasuk, nilai, daerahsalur, status) 
                              VALUES (:id, :donatur, :isibantuan, :tanggalmasuk, :nilai, :daerahsalur, 'tersalur')";
                $stmtInsert = $this->conn->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':id' => $row['id'],
                    ':donatur' => $row['donatur'],
                    ':isibantuan' => $row['isibantuan'],
                    ':tanggalmasuk' => $row['tanggalmasuk'],
                    ':nilai' => $row['nilai'],
                    ':daerahsalur' => $row['daerahsalur']
                ]);

                // 3. Delete from source
                $sqlDelete = "DELETE FROM tbantuanmasuk WHERE id = :id";
                $stmtDelete = $this->conn->prepare($sqlDelete);
                $stmtDelete->bindParam(":id", $id);
                $stmtDelete->execute();

                $this->conn->commit();
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>
