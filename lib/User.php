<?php
include_once 'session.php';
include 'Database.php';
class User
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }
    public function userRegistration($data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];
        $password = md5($data['password']);

        $chk_email = $this->emailCheck($email);
        if ($name == "" or $username == "" or $email == "" or $password == "") {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong> Field must not be Empty</div>";
            return $msg;
        }
        if (strlen($username) < 3) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>Username is too short!</div>";
            return $msg;
        } elseif (preg_match('/[^a-z0-9_-]+/i', $username)) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>Username must only contain alphanumerical, dashes and underscore!</div>";
            return $msg;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>The email address is not valid!</div>";
            return $msg;
        }
        if ($chk_email == true) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>The email address is already exits!</div>";
            return $msg;
        }
        $sql = "INSERT INTO tbl_user (name, username, email, password) VALUES (:name, :username, :email, :password)";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':name', $name);
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $result = $query->execute();
        if ($result) {
            $msg = "<div class='alert alert-success'><strong>Success ! </strong>Thank you, You have been registered!</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>There has been problem inserting your details.</div>";
            return $msg;
        }
    }
    public function emailCheck($email)
    {
        $sql = "SELECT email FROM tbl_user WHERE email= :email";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->execute();
        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getLoginUser($email, $password)
    {
        $sql = "SELECT * FROM tbl_user WHERE email = :email AND password = :password LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':email', $email);
        $query->bindValue(':password', $password);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function userLogin($data)
    {

        $email = $data['email'];
        $password = md5($data['password']);

        $chk_email = $this->emailCheck($email);
        if ($email == "" or $password == "") {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong> Field must not be Empty</div>";
            return $msg;
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>The email address is not valid!</div>";
            return $msg;
        }
        if ($chk_email == false) {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>The email address not exits!</div>";
            return $msg;
        }
        $result = $this->getLoginUser($email, $password);
        if($result){
            session::init();
            session::set("login", true);
            session::set("id", $result->id);
            session::set("name", $result->name);
            session::set("username", $result->username);
            session::set("loginmsg", "<div class='alert alert-success'><strong>Success ! </strong>You are logged in</div>");
            header("Location: index.php");
        }else{
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>Data not found</div>";
            return $msg;
        }
    }
    public function getUserData(){
        $sql = "SELECT * FROM tbl_user ORDER BY id DESC";
        $query = $this->db->pdo->prepare($sql);
        $query->execute();
        $result= $query->fetchAll();
        return $result;
    }
    public function getUserById($userid){
        $sql = "SELECT * FROM tbl_user WHERE id = :id LIMIT 1";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':id', $userid);
        
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        return $result;

    }
    public function updateUserData($userid,$data){
        $name = $data['name'];
        $username = $data['username'];
        $email = $data['email'];
      
        if ($name == "" or $username == "" or $email == "") {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong> Field must not be Empty</div>";
            return $msg;
        }
       
        $sql = "UPDATE tbl_user set 
                        name = :name,
                        username = :username,
                        email = :email
                        where id = :id
                    ";
        $query = $this->db->pdo->prepare($sql);
        $query->bindValue(':name', $name);
        $query->bindValue(':username', $username);
        $query->bindValue(':email', $email);
        $query->bindValue(':id', $userid);
        $result = $query->execute();
        if ($result) {
            $msg = "<div class='alert alert-success'><strong>Success ! </strong>User data updated successfully</div>";
            return $msg;
        } else {
            $msg = "<div class='alert alert-danger'><strong>ERROR ! </strong>User data not updated</div>";
            return $msg;
        }
    
    }
    public function changePassword($userid, $newPassword)
    {
        // Assuming you have a PDO database connection in $this->db
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $query = "UPDATE tbl_user SET password = :password WHERE id = :userid";
            $stmt = $this->db->pdo->prepare($query);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':userid', $userid);
            if ($stmt->execute()) {
                return "Password changed successfully!";
            } else {
                return "Error changing password: " . $stmt->errorInfo()[2];
            }
        } catch (PDOException $e) {
            return "Database Error: " . $e->getMessage();
        }
    }

}




