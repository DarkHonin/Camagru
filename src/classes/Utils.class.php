<?php

class Utils{

    public const verbose = false;

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
    
    public static function create_csrf_token($secret){
        $token = sha1(uniqid(rand(), TRUE).$secret);
        $_SESSION['scrf_token-'.$secret] = $token;
        $_SESSION['scrf_token_time'] = time();
        return $token;
    }

    public static function check_csrf_token($secret, $token){
        if(self::verbose) echo "Checking CsrfToken $secret : ";
        if(!isset($_SESSION['scrf_token-'.$secret])){
            if(self::verbose) echo "Not set\n";
            return false;
        }
        $age = (time() - $_SESSION['scrf_token_time']);
        if($age / 60 >= 1){
            if(self::verbose) echo "Expired ($age)min\n";
            unset($_SESSION['scrf_token-'.$secret]);
            return false;
        }
        if($token !== $_SESSION['scrf_token-'.$secret]){
            if(self::verbose) echo "Doesnt match\n";
            return false;
        }
        if(self::verbose) echo "Valid\n";
        return true;
    }
    
    public static function finalResponse($array){
        error_log("Senfing response: ".json_encode($array));
        header("Content-Type: application/json");
        echo json_encode($array);
        exit();
    }
    public static function send_token_email($email, $token){
        error_log("Sending token email to: $email");
        $message = "http://".$_SERVER['SERVER_NAME']."/activate?token=$token";
        self::sendEmail($email, $message, "Activate account");
    }

    public static function sendEmail($email, $message, $subj){
        $from = "no-reply@".$_SERVER['SERVER_NAME'];
        $to = $email;
        $subject = "$subj @ Camagru";
        $headers = "From:" . $from;
        if(!@mail($to,$subject,$message, $headers)){
            error_log(error_get_last()["message"]);
            Utils::finalResponse(["data"=>["error"=>["global"=>"Could not send email, please try again later"]], "status"=>false]);
        }
        return 1;
    }
}


?>