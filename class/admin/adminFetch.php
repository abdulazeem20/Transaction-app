<?php

require_once 'class/Db.php';
class Fetch extends Db
{
    //function to fetch the total number of users
    public function countUsers()
    {
        try {
            $stmt = $this->handle->prepare(' SELECT COUNT(id) FROM users ');
            $stmt->execute([]);
            $numOfUsers = $stmt->fetch(PDO::FETCH_COLUMN);

            return $numOfUsers;
        } catch (PDOException $e) {
            // echo 'can\'t count registered uses: '.$e->getMessage();
        }
    }

    // function to fetch the total balance of all users
    public function sumBalance()
    {
        try {
            $stmt = $this->handle->prepare('SELECT SUM(balance) totalBalance FROM balance ');
            $stmt->execute([]);
            $totalBalance = $stmt->fetch(PDO::FETCH_COLUMN);

            return $totalBalance;
        } catch (PDOException $e) {
            // echo 'can\'t sum all balance: '.$e->getMessage();
        }
    }

    //function to fetch all transaction
    public function countTransaction()
    {
        try {
            $stmt = $this->handle->prepare(' SELECT COUNT(id) FROM transaction ');
            $stmt->execute([]);
            $numOfTransaction = $stmt->fetch(PDO::FETCH_COLUMN);

            return $numOfTransaction;
        } catch (PDOException $e) {
            // echo 'can\'t count transaction: '.$e->getMessage();
        }
    }

    //function to fetch all registered Users
    public function getAllUsers()
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM users ORDER BY id DESC');
            $stmt->execute([]);
            $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $allUsers;
        } catch (PDOException $e) {
            // echo  'can\'t get Users: '.$e->getMessage();
        }
    }

    //function fetching with states
    public function getUsers($user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT users.firstname, users.othername, users.email, users.phone_no, local_governments.name lga, states.name state, users.account_no FROM users, local_governments, states WHERE users.lga = local_governments.id AND users.state = states.id AND users.id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $Users = $stmt->fetch(PDO::FETCH_ASSOC);

            return $Users;
        } catch (PDOException $e) {
            // echo  'can\'t get Users: '.$e->getMessage();
        }
    }

    //function to fetch states and local Government
    public function getUsersStatesAndLga($admin_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT local_governments.name lga, states.name state FROM admin, local_governments, states WHERE admin.lga = local_governments.id AND admin.state = states.id AND admin.id = :admin_id');
            $stmt->execute([':admin_id' => $admin_id]);
            $statesAndLga = $stmt->fetch(PDO::FETCH_ASSOC);

            return $statesAndLga;
        } catch (PDOException $e) {
            // echo 'can\'t get States and Lga: '.$e->getMessage();
        }
    }

    //function to Update Admin's Profile
    public function update($admin_id, $firstName, $otherName, $email, $phoneNumber, $state, $lga)
    {
        try {
            $stmt = $this->handle->prepare('UPDATE admin SET firstname = :firstName, othername = :otherName, email = :email, phone_no = :phoneNumber,state = :state, lga = :lga WHERE id = :admin_id');
            $stmt->execute([':admin_id' => $admin_id, ':firstName' => $firstName, ':otherName' => $otherName, ':email' => $email, ':phoneNumber' => $phoneNumber, ':state' => $state, ':lga' => $lga]);

            return true;
        } catch (PDOException $e) {
            // echo "can't update: " . $e->getMessage();
        }
    }
}