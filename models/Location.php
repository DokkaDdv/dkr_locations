<?php
// classe location

class Location {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $vehicle_id, $date_debut, $date_fin) {
        $stmt = $this->pdo->prepare("INSERT INTO location (id_user, id_vehicle, date_debut, date_fin) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $vehicle_id, $date_debut, $date_fin]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->pdo->prepare(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif
             FROM location l
             JOIN vehicles v ON l.id_vehicle = v.id
             WHERE l.id_user = ?
             ORDER BY l.created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif
             FROM location l
             JOIN vehicles v ON l.id_vehicle = v.id
             WHERE l.id_location = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getActive() {
        $stmt = $this->pdo->query(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif,
                    u.nom, u.prenom, u.email
             FROM location l
             JOIN vehicles v ON l.id_vehicle = v.id
             JOIN users u ON l.id_user = u.id
             WHERE l.date_fin > CURRENT_DATE
             ORDER BY l.date_fin ASC"
        );
        return $stmt->fetchAll();
    }

    public function getHistory() {
        $stmt = $this->pdo->query(
            "SELECT l.*, v.marque, v.modele, v.immatriculation, v.tarif,
                    u.nom, u.prenom, u.email
             FROM location l
             JOIN vehicles v ON l.id_vehicle = v.id
             JOIN users u ON l.id_user = u.id
             WHERE l.date_fin <= CURRENT_DATE
             ORDER BY l.date_fin DESC"
        );
        return $stmt->fetchAll();
    }

    public function terminate($id_location) {
        $loc = $this->getById($id_location);
        if ($loc) {
            // GREATEST evite de violer la contrainte date_fin >= date_debut
            $stmt = $this->pdo->prepare("UPDATE location SET date_fin = GREATEST(CURRENT_DATE, date_debut) WHERE id_location = ?");
            $stmt->execute([$id_location]);
            $stmt2 = $this->pdo->prepare("UPDATE vehicles SET statut = 'disponible' WHERE id = ?");
            $stmt2->execute([$loc['id_vehicle']]);
            return true;
        }
        return false;
    }

    public function calculerMontant($tarif, $date_debut, $date_fin) {
        $jours = (strtotime($date_fin) - strtotime($date_debut)) / 86400 + 1;
        return $tarif * max(1, $jours);
    }
}
?>
