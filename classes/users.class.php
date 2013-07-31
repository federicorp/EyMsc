<?php
/**
 * @name Clase de usuarios
 * @package classPHP
 * @author Federicorp
 * @copyright 2013 Federicorp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License  
 * 
 */
 
 class usuario extends singleton{
 	
	/* Campos a ser modificados de acuerdo a la base de datos*/
	
	public $conf = array(
		
		
		/* Encriptación de contraseña */
		
		"encriptacion" => true,
		
		
		/* Variables session*/
		"nombre_session_nick" => "usuario",
		"nombre_session_pass" => "pass_usuario",
		"nombre_session_id" => "id",
		"guardar_session_pass" => false,
		
		
		/* Campos de la base de datos */
		"tabla" => "usuarios",
		"campo_id" => "uid",
		"campo_nickname" => "unombre",
		"campo_pass" => "upass"
		
	);
	
     
     /** 
      * Nick Name del Usuario
      */
      
     private $nickname = null;
     
     
     /** 
      * Contraseña MD5 del usuario
      */
      
     private $pass = null;
     
     /** 
      * Id de la BD del Usuario
      */
     
     public $id = null;
     
     public $mysql = null;
     
     protected $security = null;
     
     public function __construct($obj_mysql) {
     	$this->includes($obj_mysql);
		if(!isset($_SESSION))
              session_start();
        if($this->is_loged()){
            $this->nickname = $_SESSION[$this->conf['nombre_session_nick']];
            $this->id = $_SESSION[$this->conf['nombre_session_id']];
			if($this->conf["guardar_session_pass"]){
	            if($row = $this->mysql->fetch($this->mysql->query("SELECT * FROM ".$this->conf["tabla"]." WHERE ".$this->conf["campo_id"]."='".$this->mysql->clean($this->id)."'")))
	                $this->pass = $row[$this->conf['nombre_session_pass']];
	        }
        }
     }
     
	 protected function includes($obj_mysql){
        require_once 'security.class.php';
        $this->mysql = $obj_mysql;
        $this->security= new security();
	 }
     
     /** 
      * Funcion para el login, retorna true si los datos son correctos
      * y false si no. Setea $_SESSION['usuario'].
      * 
      *  @param nickname, contraseña
      *  @return true si los datos del usuario son correctos
      */
     
     public function login($nick,$pass){
     	 $pass = $this->security->prep_encr($pass,"strrev-md5");
         if($this->mysql->contar($this->conf["tabla"], "WHERE ".$this->conf['campo_nickname']."='".$this->mysql->clean($nick)."' AND ".$this->conf['campo_pass']."='".$this->mysql->clean($pass)."'")==1){
         	$row = $this->mysql->fetch($this->mysql->query("SELECT ".$this->conf['campo_id']." FROM ".$this->conf["tabla"]." WHERE ".$this->conf['campo_nickname']."='".$this->mysql->clean($nick)."' AND ".$this->conf['campo_pass']."='".$this->mysql->clean($pass)."'"));
			$_SESSION[$this->conf['nombre_session_nick']] = $nick;
			$_SESSION[$this->conf['nombre_session_id']] = $row[$this->conf['campo_id']];
            $this->id = $row[$this->conf['campo_id']];
         	return true;
         }else{
         	return false;
		 }
     }
     
     /**
      * Función para saber si el usuario está logueado en el sistema
      * 
      *  @param none
      *  @return true si está logeado
      */
      
      public function is_loged(){
          if(isset($_SESSION[$this->conf['nombre_session_id']]))
            return true;
          return;
      }
      
      /**
      * Retorna el nombre del usuario
      * 
      * @return string
      */
      
      public function get_nombre(){
          return $this->nickname;
      }
      
      /**
      * Retorna el id del usuario
      * 
      * @return string
      */
      
      public function get_id(){
          return $this->id;
      }
      
      public function cerrar(){
          unset($_SESSION[$this->conf["nombre_session_nick"]], $_SESSION[$this->conf["nombre_session_id"]], $_SESSION[$this->conf["nombre_session_pass"]]);
      }
      
 }
