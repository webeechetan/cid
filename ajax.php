<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'cid');

if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['vote']) && isset($_POST['token']) ){

    if (!empty($_POST['token'])) {
        if (hash_equals($_SESSION['token'], $_POST['token'])) {
            $response = [
                'msg' => '',
                'status' => 'false'
            ];
        
            // validate data
        
            $name = $_POST['name'];
            $email = $_POST['email'];
            $vote = $_POST['vote'];
        
            $name = mysqli_real_escape_string($conn, $name);
            $email = mysqli_real_escape_string($conn, $email);
            $vote = mysqli_real_escape_string($conn, $vote);
        
            if(empty($name) || empty($email) || empty($vote)){
                $response['msg'] = "All fields are required";
                echo json_encode($response);
                exit;
            }
        
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $response['msg'] = "Invalid email address";
                echo json_encode($response);
                exit;
            }
        
            // check if already voted
        
            $name = strip_tags($name);
            $email = strip_tags($email);
            $vote = strip_tags($vote);
            
            $res = isAlreadyVoted($_POST['email']);
        
            if($res){
                $response['msg'] = "You have already voted";
                $response['status'] = 'false';
                echo json_encode($response);
                exit;
            }
        
            
        
            $sql = "INSERT INTO votings (name, email, vote) VALUES ('$name', '$email', '$vote')";
            $result = mysqli_query($conn, $sql);
            if($result){
                $response['msg'] = "Thank you for voting";
                $response['status'] = 'true';
                echo json_encode($response);
                exit;
            }else{
                $response['msg'] = "Something went wrong";
                $response['status'] = 'false';
                echo json_encode($response);
                exit;
            }
        } else {
            $response['msg'] = "Invalid request";
            $response['status'] = 'false';
            echo json_encode($response);
            exit;
        }
    }else{
        $response['msg'] = "Invalid request";
        $response['status'] = 'false';
        echo json_encode($response);
        exit;
    }
    
}

function isAlreadyVoted($email){
    global $conn;
    $sql = "SELECT * FROM votings WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        return true;
    }else{
        return false;
    }
}
?>