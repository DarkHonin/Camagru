<?php

class Utils{
    public static function arrayToQueryConditions($arr){
        $ret = [];
        foreach($arr as $k=>$v)
            array_push($ret, "$k='$v'");
        return $ret;
    }

    public static function arrayToInsertValues($arr){
        $ret = ["cols" => [], "vals"=>[]];
        foreach($arr as $k=>$v){
            array_push($ret["cols"], $k);
            array_push($ret["vals"], $v);
        }
        $ret["cols"] = implode(", ", $ret["cols"]);
        $ret["vals"] = "'".implode("', '", $ret["vals"])."'";
        return $ret;
    }

    public static function getCallingQuery(){
		$stack=debug_backtrace();
		foreach($stack as $st=>$call) {
			if($call['class'] != "Query" && get_parent_class($call["class"]) == "Query")
				return $call['class'];
		}
	}
}

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
    $users = select(["what"=>"*", "from"=>"users", "where" => "uname='{$uname}'"]);
    if(empty($users))
        die(["error"=>"Invalid username / password"]);
    $user = $users[0];
    if(!password_verify($password, $user['sha']))
        die(["error"=>"Invalid username / password"]);
    $_SESSION['user'] = ["uname"=>$uname, "token"=>$user['token'], "active"=>$user['active'], "id"=>$user['id']];
    return true;
}

function getUserNameForID($id){
    $users = select(["what"=>"uname", "from"=>"users", "where" => "id='{$id}'"]);
    if(empty($users))
        die(["error"=>"Invalid usernam / password"]);
    $user = $users[0];
    return $user['uname'];
}

function update_user(){
    if(!isset($_SESSION['user']))
        return false;
    $users = select(["what"=>"uname, active, token", "from"=>"users", "where" => "token='{$_SESSION['user']['token']}' AND uname='{$_SESSION['user']['uname']}'"]);
    if(empty($users))
        return false && include_once("parts/logout.php");
    $user = $users[0];
    if($user['active']){
        $_SESSION['user']['active'] = 1;
        $ntoken = sha1(time().$user['uname']);
        update(["table"=>"users", "set"=>"token='$ntoken'", "where" => "token='{$_SESSION['user']['token']}' AND uname='{$_SESSION['user']['uname']}'"]);
        $_SESSION['user']['token'] = $ntoken;
    }
    return true;
}

function send_token_email($email, $token){
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
    $from = "no-reply@".$_SERVER['SERVER_NAME'];
    $to = $email;
    $subject = "Activate account @ Camagru";
    $message = $_SERVER['SERVER_NAME']."/activate?token=$token";
    $headers = "From:" . $from;
    return(mail($to,$subject,$message, $headers));
}

?>