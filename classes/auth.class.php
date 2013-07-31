<?php
/**
 * @name Clase de Permisos
 * @package classPHP
 * @author Federicorp
 * @copyright 2013 Federicorp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License  
 * 
 */
 
 class permisos{
     
     
     /**
      * Configuraciones de Base de datos y usuarios
      */
      
     protected $conf_perm = array(
        
        /* -------- Tabla permisos -------- */
        
        "nombre_tabla_permiso" => "permisos",
        "id_permiso" => "perid",
        
        /* -------- Tabla Perfiles -------- */
        
        "nombre_tabla_perfil" => "perfiles_permisos",
        "id_perfil" => "ppid",        
        
        /* -------- Tabla de Conjunción --------- */
        
        "nombre_tabla_conj" => "perfiles_has_permisos"
     
     );
     
     /**
      * Variable cache de permisos del usuario
      */
      
      private $cache_permisos = array();
      
      
      private $usu;
      
      
      public function __construct($object){
        $this->usu = $object;
     }
      
     /**
      * 
      * Retorna si tiene o no permisos para realizar las acciones
      * 
      */ 
     
     public function have_permisos($permiso){
         if(isset($this->cache_permisos[$permiso])){
             return $this->cache_permisos[$permiso];
         }else{
             $row = $this->usu->mysql->fetch($this->usu->mysql->query("SELECT ".$this->conf_perm['id_perfil']." FROM ".$this->usu->conf["tabla"]." WHERE ".$this->usu->conf['campo_id']."='".$this->usu->mysql->clean($this->usu->get_id())."'"),"assoc");
             $counter = $this->usu->mysql->contar($this->conf_perm["nombre_tabla_conj"],"WHERE ".$this->conf_perm['id_permiso']."='".$this->usu->mysql->clean($permiso)."' AND ".$this->conf_perm["id_perfil"]."='".$this->usu->mysql->clean($row[$this->conf_perm['id_perfil']])."'");
             if($counter!=0){
                 $this->cache_permisos[$permiso]=TRUE;
                 return true;
             }
             $this->cache_permisos[$permiso]=FALSE;
             return false;
         }
     }
     
     public function can_login(){
         if($this->have_permisos(1))
            return true;
         return false;
     }
     
 }
?>