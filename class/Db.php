<?php

class Db
{
    public $handle;
    public $db_name = 'transactions';
    public $db_user = 'root';
    public $db_password = 'sijuade';

    public function __construct()
    {
        //default function that loads itself
        //calling the database connection function
        $this->handle = $this->dbEngine();
    }

    public function dbEngine()
    {
        try {
            $this->handle = new PDO("mysql:host=localhost;dbname=$this->db_name", $this->db_user, $this->db_password);
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo ("Database Connection successful");
            return $this->handle;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}