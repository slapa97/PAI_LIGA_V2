<?php

require_once 'User.php';
require_once __DIR__.'/../Database.php';

class UserMapper
{
    private $database;

    public function __construct()
    {
        $this->database = new Database();
    }


    public function addUser(
        string $name, string $surname, string $email, string $password
    ) {
        try {
            $stmt = $this->database->connect()->prepare('INSERT INTO Users (email, password, name, surname, id_role) VALUES (:email, :password, :name, :surname, 2);');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return 'User registered!';
        }
        catch(PDOException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function getUser(string $email):User {
        try {
            $stmt = $this->database->connect()->prepare('SELECT * FROM Users INNER JOIN Role WHERE email = :email;');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();


            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            //return new User($user['name'], $user['surname'], $user['email'], $user['password']);
            return new User($user['name'], $user['surname'], $user['email'], $user['password'],$user['role']);
        }
        catch(PDOException $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function getUsers()
    {
        try {
            $stmt = $this->database->connect()->prepare('SELECT * FROM Users WHERE email != :email;');
            $stmt->bindParam(':email', $_SESSION['id'], PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $user;
        }
        catch(PDOException $e) {
            die();
        }
    }

    public function delete(int $id): void
    {
        try {
            $stmt = $this->database->connect()->prepare('DELETE FROM Users WHERE id = :id;');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        catch(PDOException $e) {
            die();
        }
    }
}