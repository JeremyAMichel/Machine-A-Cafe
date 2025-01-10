<?php

final class MachineACafeController
{
    private MachineACafe $machine;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['machine'])) {
            $_SESSION['machine'] = new MachineACafe("Senseo");
        }
        $this->machine = $_SESSION['machine'];
    }

    public function handleRequest()
    {
        // Log the contents of $_POST
        // error_log("POST data: " . print_r($_POST, true)); // Default location is C:\wamp64\logs\php_error.log

        $action = $_POST['action'] ?? '';

        $response = '';

        switch ($action) {
            case 'on_off':
                $this->machine->allumage();
                $response = $this->machine->getEstEnFonction() ? "Machine allumée" : "Machine éteinte";
                $action = "on_off";
                break;
            case 'add_dosette':
                if($this->machine->getNombreDossettes() >= 1) {
                    $response = "Impossible d'ajouter une dosette";
                    $action = "add_dosette";
                    break;
                }
                $this->machine->ajouterDossettes();
                $response = "Dosette ajoutée";
                $action = "add_dosette";
                break;
            case 'make_coffee':
                if($this->machine->getNombreDossettes() < 1) {
                    $response = "Il faut une dossette pour faire un café";
                    $action = "make_coffee";
                    break;
                }
                if(!$this->machine->getEstEnFonction()) {
                    $response = "Il faut allumer la machine pour faire un café";
                    $action = "make_coffee";
                    break;
                }
                $this->machine->faireCafe();
                $response = "Café prêt";
                $action = "make_coffee";
                break;
            default:
                $response = 'Action non reconnue';
                break;
        }

        echo json_encode(['message' => $response, 'action' => $action, 'state' => [
            'marque' => $this->machine->getMarque(),
            'nombreDossettes' => $this->machine->getNombreDossettes(),
            'estEnFonction' => $this->machine->getEstEnFonction()
        ]]);
    }
}
