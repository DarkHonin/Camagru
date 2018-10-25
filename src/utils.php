<?php

function create_csrf_token($secret){
    $token = sha1(uniqid(rand(), TRUE).$secret);
    $_SESSION['scrf_token-'.$secret] = $token;
    $_SESSION['scrf_token_time'] = time();
    return $token;
}

function check_scrf_token($secret){
    if(!isset($_SESSION['scrf_token-'.$secret]))
        return false;
    $age = (time() - $_SESSION['scrf_token_time']);
    if($age / 60 >= 1)
        return false;
    if($_POST['scrf'] !== $_SESSION['scrf_token-'.$secret])
        return false;
    unset($_SESSION['scrf_token-'.$secret]);
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

function validate_post(&$fields){
    foreach($fields as $k=>$f){
        if(isset($f["required"]) && (!isset($_POST[$f['name']]) || empty($_POST[$f['name']])))
            return [$f['name'] => "Field is required"];
        if(isset($f["maxlength"]) && strlen($_POST[$f['name']]) > $f["maxlength"])
            return [$f['name'] => "Maximum amount of characters: {$f["maxlength"]}" ];
        if(isset($_POST[$f['name']]))
            $fields[$k]['value'] = $_POST[$f['name']];
    }
}

?>