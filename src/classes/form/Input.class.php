<?php

class Input{

    public static function PASSWORD($name, $id=null, $class=null, $placeholder=null){
        return new Input(["name"=>$name, "type"=>"password", "required"=>"true", "id"=>$id,"placeholder"=>$placeholder, "class"=>$class, "pattern"=>"^(?=.*[A-Z])(?=.*[!\-+@#$&*])(?=.*\d)(?=.*[a-z]).*$", "minlength"=>8]);
    }

    public static function USERNAME($name, $id=null, $class=null, $placeholder=null){
        return new Input(["name"=>$name, "type"=>"text", "id"=>$id, "required"=>"true", "placeholder"=>$placeholder, "class"=>$class, "pattern"=>"^[0-9a-zA-Z_\-]{1,}$", "minlength"=>6, "maxlength"=>16]);
    }

    public static function EMAIL($name, $id=null, $class=null, $placeholder=null){
        return new Input(["name"=>$name, "type"=>"email", "id"=>$id, "required"=>"true", "placeholder"=>$placeholder, "class"=>$class]);
    }

    public static function SUBMIT($value, $id=null, $class=null){
        return new Input(["type"=>"submit", "id"=>$id, "class"=>$class, "value"=>$value]);
    }

    public static function HIDDEN($name, $value){
        return new Input(["type"=>"hidden", "name"=>$name, "value"=>$value]);
    }

    public static function CSRF_TOKEN($secret){
        require_once("src/classes/Utils.class.php");
        return self::HIDDEN("csrf-token", Utils::create_csrf_token($secret));
    }

    public const verbose = false;
    public const errors = [
        "maxlength" => "The field '%s' can not be longer than %s",
        "minlength" => "The field '%s' can not be shorter than %s",
        "required" => "The field '%s' is required",
        "pattern" => "The field '%s' does not math the pattern",
        "max"       => "The value of '%s' can not be greater than %s",
        "min"   =>  "The value of '%s' can not be less than %s",
        "type"  => "Invalid data type for field '%s'"
    ];

    public const type_filters = [
        "email" => FILTER_VALIDATE_EMAIL,
        "number" => FILTER_VALIDATE_FLOAT
    ];

    public $name;
    public $required;
    public $value;
    public $id;
    public $class;
    public $maxlength;
    public $minlength;
    public $pattern;
    public $type;
    public $hidden;
    public $min;
    public $max;
    public $placeholder;
    public $onblur;

    function __construct($preconfig){
        foreach(get_class_vars(get_class($this)) as $k=>$v){
			if(self::verbose) echo "$k($v) = {$preconfig[$k]}\n";
            if(isset($preconfig[$k]) && $preconfig[$k])
                $this->$k = $preconfig[$k];
		}
    }

    function ck_maxlength(){ return strlen($this->value) < $this->maxlength; }
    function ck_minlength(){ return strlen($this->value) >= $this->minlength; }
    function ck_required(){ return $this->required && $this->value; }
    function ck_min(){ return intval($this->value) > $this->min; }
    function ck_max(){ return intval($this->value) < $this->max; }
    function ck_type(){ 
        if(!isset(self::type_filters[$this->type])) return true;
        return filter_var($this->value, self::type_filters[$this->type]); 
    }

    function ck_pattern(){
        $res = [];
        preg_match("/$this->pattern/", $this->value, $res);
        return !empty($res);
    }
    
    function valid(&$err = []){
        foreach(get_class_vars(get_class($this)) as $k=>$v){
            if(!isset($this->$k))
                continue;
            $ckstr = "ck_$k";
            if(self::verbose) echo "Checking $ckstr : ";
            if(method_exists($this, $ckstr))
                if(!$this->$ckstr()){
                    array_push($err, sprintf(self::errors[$k], $this->placeholder, $this->$k));
                    if(self::verbose) echo "No-Joy\n";
                }
                if(self::verbose) echo "OK\n";
        }
        if(!empty($err)) return false;
        return true;
    }

    function render(){
        echo "<fieldset><input";
        foreach(get_class_vars(get_class($this)) as $k=>$v){
            if(isset($this->$k) && !empty($this->$k))
                echo " $k='{$this->$k}'";
        }
        echo ">";
        echo "<span class='invalid error'></span></fieldset>";
    }
}

?>