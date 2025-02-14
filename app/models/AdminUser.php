<?php

class AdminUser
{
    private $db;

    public function __construct()
    {
        $this->db = MySQLdb::getInstance()->getDatabase();
    }

    public function createAdminUser($data)
    {
        $response = false;

        if ( ! $this->existsEmail($data['email'])) {

            $pass = hash_hmac('sha512', $data['password'], ENCRIPTKEY);

            $sql = 'INSERT INTO admins(name, email, password, status, deleted, login_at, created_at, updated_at, deleted_at) 
                    VALUES(:name, :email, :password, :status, :deleted, :login_at, :created_at, :updated_at, :deleted_at)';
            $params = [
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password' => $pass,
                ':status' => 1,
                ':deleted' => 0,
                ':login_at' => null,
                ':created_at' => date('Y-m-d H:i:s'),
                ':updated_at' => null,
                ':deleted_at' => null,
            ];
            $query = $this->db->prepare($sql);
            $response = $query->execute($params);

        if ($response == 0) {
            return $response;
        }

        $sql2 = 'INSERT INTO users(adminUser, first_name, email, password) 
            VALUES (1, :name, :email, :password)';
        $params2 = [
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $pass,
        ];

        $query2 = $this->db->prepare($sql2);
        $response = $response + $query2->execute($params2);
    }
        return $response;
    }

    public function existsEmail($email)
    {
        $sql = 'SELECT * FROM admins WHERE email=:email AND deleted=0';
        $query = $this->db->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        $count =  (int) $query->rowCount();

        $sql2 = 'SELECT * FROM users WHERE email=:email';
        $query2 = $this->db->prepare($sql2);
        $query2->bindParam(':email', $email, PDO::PARAM_STR);
        $query2->execute();

        return  $count + (int) $query2->rowCount();
    }

    public function getUsers()
    {
        $sql = 'SELECT * FROM admins WHERE deleted=0';
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getUserById($id)
    {
        $sql = 'SELECT * FROM admins WHERE id=:id';
        $query = $this->db->prepare($sql);
        $query->execute([':id' => $id]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getConfig($type)
    {
        $sql = 'SELECT * FROM config WHERE type=:type ORDER BY value DESC';
        $query = $this->db->prepare($sql);
        $query->execute([':type' => $type]);

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function setUser($user)
    {
        $errors = [];

        if ( ! empty($user['password']) ) {
            $sql = 'UPDATE admins SET name=:name, email=:email, password=:password, status=:status, updated_at=:updated_at WHERE id=:id';
            $password = hash_hmac('sha512', $user['password'], ENCRIPTKEY);
            $params = [
                ':id' => $user['id'],
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':password' => $password,
                ':status' => $user['status'],
                ':updated_at' => date('Y-m-d H:i:s'),
            ];
        } else {
            $sql = 'UPDATE admins SET name=:name, email=:email, status=:status, updated_at=:updated_at WHERE id=:id';

            $params = [
                ':id' => $user['id'],
                ':name' => $user['name'],
                ':email' => $user['email'],
                ':status' => $user['status'],
                ':updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $query = $this->db->prepare($sql);
        if ( ! $query->execute($params) ) {
            array_push($errors, 'Error al modificar al usuario');
        }

        return $errors;
    }

    public function delete($id)
    {
        $errors = [];

        $sql = 'UPDATE admins SET deleted=1, deleted_at=:deleted_at WHERE id=:id';

        $query = $this->db->prepare($sql);

        $params = [
            ':id' => $id,
            ':deleted_at' => date('Y-m-d H:i:s'),
        ];

        if ( ! $query->execute($params) ) {
            array_push($errors, 'Error al eliminar al usuario');
        }

        return $errors;
    }
}