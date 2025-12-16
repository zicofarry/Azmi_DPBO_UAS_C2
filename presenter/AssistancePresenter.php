<?php
/*
 * AssistancePresenter Class
 * Handles user requests and communicates with the Model.
 */
require_once 'model/AssistanceModel.php';

class AssistancePresenter {
    private $model;

    public function __construct() {
        $this->model = new AssistanceModel();
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Add New Assistance
            if (isset($_POST['action']) && $_POST['action'] === 'add') {
                $data = [
                    'id' => $_POST['id'],
                    'daerahsalur' => $_POST['daerahsalur'],
                    'donatur' => $_POST['donatur'],
                    'tanggalmasuk' => $_POST['tanggalmasuk'],
                    'nilai' => $_POST['nilai'],
                    'isibantuan' => $_POST['isibantuan']
                ];
                $this->model->addAssistance($data);
            }
            
            // 2. Update Incoming Assistance (Inline Edit)
            elseif (isset($_POST['action']) && $_POST['action'] === 'update_incoming') {
                $this->model->updateIncoming($_POST['id'], $_POST['nilai'], $_POST['status']);
            }

            // 3. Delete Incoming Assistance
            elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $this->model->deleteIncoming($_POST['id']);
            }

            // 4. Distribute Assistance (Move to tbantuansalur)
            elseif (isset($_POST['action']) && $_POST['action'] === 'distribute') {
                $this->model->distributeAssistance($_POST['id']);
            }

            // 5. Update Distributed Assistance (e.g. set to 'hilang')
            elseif (isset($_POST['action']) && $_POST['action'] === 'update_distributed') {
                $this->model->updateDistributed($_POST['id'], $_POST['status']);
            }

            // Refresh page to reflect changes
            header("Location: index.php");
            exit();
        }
    }

    public function getIncomingList() {
        return $this->model->getIncomingAssistance();
    }

    public function getDistributedList() {
        return $this->model->getDistributedAssistance();
    }
}
?>
