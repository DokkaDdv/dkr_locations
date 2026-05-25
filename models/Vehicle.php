<?php
// classe vehicle

class Vehicle {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // get tous les vehicles
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM vehicles ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    // get vehicle par id
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // ajouter vehicle
    public function add($marque, $modele, $immatriculation, $tarif, $kilometrage, $statut) {
        $stmt = $this->pdo->prepare("INSERT INTO vehicles (marque, modele, immatriculation, tarif, kilometrage, statut) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$marque, $modele, $immatriculation, $tarif, $kilometrage, $statut]);
    }
    
    // modifier vehicle
    public function update($id, $marque, $modele, $immatriculation, $tarif, $kilometrage, $statut) {
        $stmt = $this->pdo->prepare("UPDATE vehicles SET marque = ?, modele = ?, immatriculation = ?, tarif = ?, kilometrage = ?, statut = ? WHERE id = ?");
        return $stmt->execute([$marque, $modele, $immatriculation, $tarif, $kilometrage, $statut, $id]);
    }
    
    // supprimer vehicle
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // compter les vehicles par statut
    public function countByStatus($statut) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM vehicles WHERE statut = ?");
        $stmt->execute([$statut]);
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // count tous les vehicules
    public function countAll() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM vehicles");
        $result = $stmt->fetch();
        return $result['total'];
    }

    // lister les vehicules disponibles
    public function getAvailable() {
        $stmt = $this->pdo->query("SELECT * FROM vehicles WHERE statut = 'disponible' ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // vehicules disponibles a partir d'une date (exclut ceux en location jusqu'a cette date)
    public function getAvailableByDate($date_debut) {
        $stmt = $this->pdo->prepare(
            "SELECT v.* FROM vehicles v
             WHERE v.statut NOT IN ('maintenance', 'reserve')
             AND v.id NOT IN (
                 SELECT vehicle_id FROM location WHERE date_fin >= ?
             )
             ORDER BY v.id DESC"
        );
        $stmt->execute([$date_debut]);
        return $stmt->fetchAll();
    }

    // recuperer les reservations d'un vehicule
    public function getReservations($vehicle_id) {
        $stmt = $this->pdo->prepare("SELECT r.*, u.email FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.vehicle_id = ?");
        $stmt->execute([$vehicle_id]);
        return $stmt->fetchAll();
    }
}
?>
