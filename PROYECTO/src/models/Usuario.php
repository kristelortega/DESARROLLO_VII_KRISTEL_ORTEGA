<?php

namespace Models;

use PDO;
use Exception;

class Usuario
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al buscar usuario por ID: " . $e->getMessage());
            return null;
        }
    }

    public function findByEmail($email)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al buscar usuario por email: " . $e->getMessage());
            return null;
        }
    }

    public function create($userInfo)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO usuarios (id, email, nombre) VALUES (:id, :email, :nombre) 
    ON DUPLICATE KEY UPDATE email = VALUES(email), nombre = VALUES(nombre)");
            $stmt->execute([
                'id' => $userInfo['google_id'],
                'email' => $userInfo['email'],
                'nombre' => $userInfo['nombre']
            ]);
        } catch (PDOException $e) {
            error_log("Error al guardar el usuario: " . $e->getMessage());
        } catch (Exception $e) {
            echo "Error general: " . $e->getMessage();
        }
    }
}
