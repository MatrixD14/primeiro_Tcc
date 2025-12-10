<?php 
require_once './env.php';
Env::load();

class login{
    private $connect;
    public $nome,$email,$senha;
    public function __construct($nome,$email,$senha){
        $this->nome=$nome;
        $this->email=$email;
        $this->senha=$senha;
        $this->connect();
    }
    public function connect(){
        $this->connect=new mysqli($_ENV["HOST"],$_ENV["USER"],$_ENV["PASSWORD"],$_ENV["DATABASE"]);
        if($this->connect->connect_error) die("error connect on database");
        return $this->connect;
    }
    public function create_login(){
        $tmg = $this->connect->prepare("insert into usuario(nome,email,senha)values(?,?,?)");
        $tmg->bind_param("sss",$this->nome,$this->email,$this->senha);
        return $tmg->execute() or die("commad nao executado");
    }
}