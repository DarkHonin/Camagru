<?php

function create_csrf_token($secret){
    $token = sha1(uniqid(rand(), TRUE).$secret);
    $_SESSION['scrf_token-'.$secret] = $token;
    $_SESSION['scrf_token_time'] = time();
    return $token;
}

function check_csrf_token($secret, $token){
    if(!isset($_SESSION['scrf_token-'.$secret]))
        return false;
    $age = (time() - $_SESSION['scrf_token_time']);
    if($age / 60 >= 1){
        unset($_SESSION['scrf_token-'.$secret]);
        return false;
    }
    if($token !== $_SESSION['scrf_token-'.$secret])
        return false;
    return true;
}

function login($uname, $password){
    $users = select(["what"=>"sha, uname, active, token", "from"=>"users", "where" => "uname='{$uname}'"]);
    if(empty($users))
        return false;
    $user = $users[0];
    if(!password_verify($password, $user['sha']))
        return false;
    $_SESSION['user'] = ["uname"=>$uname, "token"=>$user['token'], "active"=>$user['active']];
    return true;
}

function update_user(){
    $users = select(["what"=>"uname, active, token", "from"=>"users", "where" => "token='{$_SESSION['user']['token']}' AND uname='{$_SESSION['user']['uname']}'"]);
    if(empty($users))
        return include_once("parts/logout.php");
    $user = $users[0];
    $ntoken = sha1(time().$user['uname']);
    update(["table"=>"users", "set"=>"token='$ntoken'", "where" => "token='{$_SESSION['user']['token']}' AND uname='{$_SESSION['user']['uname']}'"]);
    $_SESSION['user']['token'] = $ntoken;
}

?>