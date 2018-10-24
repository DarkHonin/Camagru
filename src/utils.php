<?php

function create_csrf_token($secret){
    $token = sha1(uniqid(rand(), TRUE).$secret);
    $_SESSION['scrf_token-'.$secret] = $token;
    $_SESSION['scrf_token_time'] = time();
    return $token;
}

function check_scrf_token($secret){
    $age = (time() - $_SESSION['scrf_token_time']);
    if($age / 60 >= 0.5)
        return false;
    if($_POST['scrf'] !== $_SESSION['scrf_token-'.$secret])
        return false;
    return true;
}

function login($uname, $password){
    $users = select(["what"=>"sha, uname, active", "from"=>"users", "where" => "uname='{$uname}'"]);
    if(empty($users))
        return false;
    $user = $users[0];
    if(!password_verify($password, $user['sha']))
        return false;
    $_SESSION['user'] = $user;
    return true;
}

function validate_post(&$fields){
    foreach($fields as $k=>$f){
        if(isset($f["required"]) && (!isset($_POST[$f['name']]) || empty($_POST[$f['name']])))
            return "Field is required: {$f['placeholder']}";
        if(isset($f["maxlength"]) && strlen($_POST[$f['name']]) > $f["maxlength"])
            return "Field '{$f['placeholder']}' must be shorter than {$f["maxlength"]} characters";
        $fields[$k]['value'] = $_POST[$f['name']];
    }
}

?>