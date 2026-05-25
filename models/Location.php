<?php
// classe location

class Location {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $vehicle_id, $date_debut, $date_fin) {
        $stmt = $this->pdo->prepare("INSERT INTO location (user_id, vehicle_id, date_debut, date_fin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $vehicle_id, $date_debut, $date_fin]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->pdo->prepare(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif
             FROM location l
             JOIN vehicles v ON l.vehicle_id = v.id
             WHERE l.user_id = ?
             ORDER BY l.created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif
             FROM location l
             JOIN vehicles v ON l.vehicle_id = v.id
             WHERE l.id_location = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function calculerMontant($tarif, $date_debut, $date_fin) {
        $jours = (strtotime($date_fin) - strtotime($date_debut)) / 86400 + 1;
        return $tarif * max(1, $jours);
    }
}
?>
