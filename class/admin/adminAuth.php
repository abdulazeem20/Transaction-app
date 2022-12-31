<?php

require_once 'class/Db.php';

    class Admin extends Db
    {
        public function login($email, $password)
        {
            try {
                $stmt = $this->handle->prepare('SELECT * FROM admin WHERE email = :email AND password = :password');
                $stmt->execute([':email' => $email, ':password' => $password]);
                $user = $stmt->fetch();

                return $user;
            } catch (PDOException $e) {
                // echo "can't fetch: ".$e->getMessage();
            }
        }

        public function getAdminDetails($id)
        {
            try {
                $stmt = $this->handle->prepare('SELECT * FROM admin WHERE id = :id');
                $stmt->execute([':id' => $id]);
                $userDetails = $stmt->fetch();

                return $userDetails;
            } catch (PDOException $e) {
                // echo "can't get details: ".$e->getMessage();
            }
        }
    }
