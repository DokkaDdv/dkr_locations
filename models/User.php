<?php
// classe user

class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // verifie les id de connexion
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ? AND password = MD5(?)");
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch();
        
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        
        return false;
    }
    
    // check si connecté
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // verif si est admin
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    // verif si est commercial
    public function isCommercial() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'commercial';
    }

    // verif si est client
    public function isClient() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client';
    }

    // creer un compte
    public function register($email, $password, $role, $nom = '', $prenom = '', $telephone = '') {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role, nom, prenom, telephone) VALUES (?, MD5(?), ?, ?, ?, ?)");
            return $stmt->execute([$email, $password, $role, $nom, $prenom, $telephone]);
        } catch (\PDOException $e) {
            // fallback si colonnes nom/prenom/telephone pas encore creees
            $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, MD5(?), ?)");
            return $stmt->execute([$email, $password, $role]);
        }
    }

    // recuperer les infos d'un user
    public function getUserInfo() {
        if ($this->isLoggedIn()) {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        }
        return null;
    }

    // modifier le mot de passe
    public function updatePassword($user_id, $old_password, $new_password) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE id = ? AND password = MD5(?)");
        $stmt->execute([$user_id, $old_password]);
        if (!$stmt->fetch()) {
            return false;
        }
        $stmt2 = $this->pdo->prepare("UPDATE users SET password = MD5(?) WHERE id = ?");
        return $stmt2->execute([$new_password, $user_id]);
    }

    // liste des clients
    public function getClients() {
        $stmt = $this->pdo->query(
            "SELECT u.*, COUNT(l.id_location) as nb_locations
             FROM users u
             LEFT JOIN location l ON l.id_user = u.id
             WHERE u.role = 'client'
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    // se deconnecter
    public function logout() {
        session_destroy();
    }
}
?>
