<?php

require_once __DIR__ . '/../Db.php';

class Auth extends Db
{
    public $handle;
    public $auth;

    public function __construct()
    {
        //default function that loads itself
        //calling the database connection function
        $this->auth = new Db();
        $this->handle = $this->auth->handle;
    }

    /* Login */
    public function adminLogin($email, $password)
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM admin WHERE email = :email AND password = :password');
            $stmt->execute([':email' => $email, ':password' => $password]);
            $user = $stmt->fetch();

            return $user;
        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }

    /* Register */
    public function register($firstname, $othername, $email, $password, $phone_no, $state, $lga, $account_no)
    {
        try {
            //Saving into registration table
            $stmt = $this->handle->prepare("INSERT INTO users (firstname, othername, email, password, phone_no, state, lga, account_no) VALUES (:firstname, :othername, :email, :password, :phone_no, :state, :lga, :account_no)");
            $stmt->execute([':firstname' => $firstname, ':othername' => $othername, ':email' => $email, ':password' => $password, ':phone_no' => $phone_no, ':state' => $state, ':lga' => $lga, ':account_no' => $account_no]);

            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }

    public function initializeBalance($user_id, $account_no)
    {
        try {
            $balance = 0.00;
            //Saving into Balance Table -- Initializing balance for user
            $stmt = $this->handle->prepare('INSERT INTO balance (user_id, balance, account_no) VALUES (:user_id, :balance, :account_no)');
            $stmt->execute([':user_id' => $user_id, ':balance' => $balance, ':account_no' => $account_no]);

            return true;
        } catch (PDOException $e) {
            // echo $e->getMessage();
        }
    }

    public function login($email, $password)
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
            $stmt->execute([':email' => $email, ':password' => $password]);
            $user = $stmt->fetch();

            return $user;
        } catch (PDOException $e) {
            // echo "can't fetch: " . $e->getMessage();
        }
    }

    public function checkAccountNo($account_no)
    {
        try {
            $stmt = $this->handle->prepare('SELECT COUNT(account_no) FROM users where account_no = :account_no ');
            $stmt->execute([':account_no' => $account_no]);
            $countAccountNum = $stmt->fetch(PDO::FETCH_COLUMN);
            // echo 'counted!!!';

            return  $countAccountNum;
        } catch (PDOException $e) {
            // echo 'can\'t count account number: '.$e->getMessage();
        }
    }

    public function checkEmail($email)
    {
        try {
            $stmt = $this->handle->prepare('SELECT email, id FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $user['email'];
            $id = $user['id'];
            $no_email = $stmt->rowCount();

            return json_encode(['email' => $email, 'no_email' => $no_email, 'id' => $id]);
        } catch (PDOException $e) {
            // echo "can't fetch: ".$e->getMessage();
        }
    }

    public function getUserDetails($id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            return $userDetails;
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function getStates()
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM states');
            $stmt->execute();
            $states = $stmt->fetchAll();

            return $states;
        } catch (PDOException $e) {
            // echo 'cant get states: '.$e->getMessage();
        }
    }

    public function getLgas($stateId)
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM local_governments WHERE state_id = :stateId');
            $stmt->execute([':stateId' => $stateId]);
            $lgas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($lgas);
        } catch (PDOException $e) {
            // echo "can't get corresponding lgas: ".$e->getMessage();
        }
    }

    public function getAllTransactions($user_id)
    {
        try {
            // SELECT * FROM transaction WHERE creator_id = :user_id OR recipent_id = :user_id
            $stmt = $this->handle->prepare(' SELECT t.*, u1.firstname creatorFirstName, u1.othername creatorLastName, u2.firstname recipentFirstName, u2.othername  recipentLastName FROM transaction t INNER JOIN users u1 ON t.creator_id = u1.id INNER JOIN users u2 ON t.recipent_id = u2.id WHERE creator_id = :user_id OR recipent_id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($transactions);
        } catch (PDOException $e) {
            // echo "can't get corresponding lgas: ".$e->getMessage();
        }
    }

    public function getCredit($id, $amount)
    {
        try {
            $stmt = $this->handle->prepare('INSERT INTO savings (user_id, amount)  VALUES(:id, :amount)');
            $stmt->execute([':id' => $id, ':amount' => $amount]);
            // echo 'sucessfully saved';

            return  true;
        } catch (PDOException $e) {
            // echo 'can\'t save money: ' . $e->getMessage();
        }
    }

    public function saveIntoTransaction($creator_id, $transact_type, $recipent_id, $amount, $transfer = 0)
    {
        try {
            $stmt = $this->handle->prepare('INSERT INTO transaction (creator_id, transact_type, recipent_id, amount, transfer)  VALUES(:creator_id, :transact_type, :recipent_id, :amount, :transfer)');
            $stmt->execute([':creator_id' => $creator_id, ':transact_type' => $transact_type, ':recipent_id' => $recipent_id, ':amount' => $amount, ':transfer' => $transfer]);

            return  true;
        } catch (PDOException $e) {
            // echo 'can\'t save into transaction table: ' . $e->getMessage();
        }
    }

    public function updateBalance($user_id, $amount)
    {
        try {
            //Updating balance with the transaction made
            $stmt2 = $this->handle->prepare('UPDATE balance SET balance = :amount WHERE user_id = :user_id');
            $stmt2->execute([':amount' => $amount, ':user_id' => $user_id]);
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function getBalance($user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT balance FROM balance WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $userBalDetails = $stmt->fetch();
            $userBal = $userBalDetails['balance'];

            return $userBal;
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function getBalanceFromCategory($transact_type, $creator_id, $recipent_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT SUM(amount) total FROM transaction WHERE transact_type = :transact_type AND creator_id = :creator_id AND recipent_id = :recipent_id');
            $stmt->execute([':transact_type' => $transact_type, ':creator_id' => $creator_id, ':recipent_id' => $recipent_id]);
            $total = $stmt->fetch();
            $totalBal = $total['total'];

            return $totalBal;
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function getTransferSent($user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT SUM(amount) total FROM transfer WHERE sender_id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $total = $stmt->fetch();
            $totalBal = $total['total'];

            return $totalBal;
        } catch (PDOException $e) {
            // echo "can't get details: " . $e->getMessage();
        }
    }

    public function getTransferReceived($user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT SUM(amount) total FROM transfer WHERE receiver_id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $total = $stmt->fetch();
            $totalBal = $total['total'];

            return $totalBal;
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function getAccountDetails($search, $user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT * FROM users WHERE (othername LIKE :search OR firstname LIKE :search OR account_no LIKE :search) AND id != :user_id');
            $search = '%' . $search . '%';
            $stmt->execute([':search' => $search, ':user_id' => $user_id]);
            $accountDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($accountDetails);
        } catch (PDOException $e) {
            // echo "can't get details: ".$e->getMessage();
        }
    }

    public function saveIntoTransfer($sender_id, $receiver_id, $amount)
    {
        try {
            $stmt = $this->handle->prepare('INSERT INTO transfer (sender_id, receiver_id, amount)  VALUES(:sender_id, :receiver_id, :amount) ');
            $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'amount' => $amount]);

            return true;
        } catch (PDOException $e) {
            // echo 'can\'t make the transfer: ' . $e->getMessage();
        }
    }

    //function to fetch states and local Government
    public function getUsersStatesAndLga($user_id)
    {
        try {
            $stmt = $this->handle->prepare('SELECT local_governments.name lga, states.name state FROM users, local_governments, states WHERE users.lga = local_governments.id AND users.state = states.id AND users.id = :user_id');
            $stmt->execute([':user_id' => $user_id]);
            $statesAndLga = $stmt->fetch(PDO::FETCH_ASSOC);

            return $statesAndLga;
        } catch (PDOException $e) {
            // echo 'can\'t get States and Lga: '.$e->getMessage();
        }
    }

    //function to Update Users Profile
    public function update($user_id, $firstName, $otherName, $email, $phoneNumber, $state, $lga)
    {
        try {
            $stmt = $this->handle->prepare('UPDATE users SET firstname = :firstName, othername = :otherName, email = :email, phone_no = :phoneNumber,state = :state, lga = :lga WHERE id = :user_id');
            $stmt->execute([':user_id' => $user_id, ':firstName' => $firstName, ':otherName' => $otherName, ':email' => $email, ':phoneNumber' => $phoneNumber, ':state' => $state, ':lga' => $lga]);

            return true;
        } catch (PDOException $e) {
            // echo "can't update: ".$e->getMessage();
        }
    }
}