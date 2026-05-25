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

    // se deconnecter
    public function logout() {
        session_destroy();
    }
}
?>
