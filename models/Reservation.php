<?php 
// classe reservation

class Reservation{
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // creer une reservation
    public function create($user_id, $vehicle_id, $start_date, $end_date){
        $stmt = $this->pdo->prepare("INSERT INTO reservations (user_id, vehicle_id, start_date, end_date) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $vehicle_id, $start_date, $end_date]);
    }

    // recuperer les reservations d'un client
    public function getByUserID($user_id){
        $stmt = $this->pdo->prepare("SELECT r.*, v.make, v.model FROM reservations r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    // recuperer une reservation par son id
    public function getByID($id){
        $stmt = $this->pdo->prepare("SELECT r.*, v.make, v.model FROM reservations r JOIN vehicles v ON r.vehicle_id = v.id WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // changer le statut d'un vehicule
    public function updateVehicleStatus($vehicle_id, $status){
        $stmt = $this->pdo->prepare("UPDATE vehicles SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $vehicle_id]);
    }

    // annuler une reservation
    public function cancel($id){
        // recuperer la reservation
        $reservation = $this->getByID($id);
        if ($reservation) {
            // mettre a jour le statut du vehicule
            $this->updateVehicleStatus($reservation['vehicle_id'], 'available');

            // supprimer la reservation
            $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }

    // verifier disponibilite d'un vehicule
    public function isVehicleAvailable($vehicle_id, $start_date, $end_date){
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE vehicle_id = ? AND (start_date <= ? AND end_date >= ?)");
        $stmt->execute([$vehicle_id, $end_date, $start_date]);
        return $stmt->fetchColumn() == 0;
    }

    // calculer le prix total d'une reservation
    public function calculateTotalPrice($vehicle_id, $start_date, $end_date){
        $stmt = $this->pdo->prepare("SELECT tarif FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicle_id]);
        $tarif = $stmt->fetchColumn();
        $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;
        return $tarif * $days;
    }
}

?>
