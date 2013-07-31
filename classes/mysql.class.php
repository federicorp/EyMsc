<?php

/**
 * Archivo de funciones mysqli, recibe como parámetros constructores la direccion  el archivo de configuracion.
 * Con las constantes: 
 * HOSTSQL,USUARIOSQL,PASSSQL,DBSQL
 * 
 * @name Clase MySQLi
 * @package classPHP
 * @author Federicorp
 * @copyright 2013 Federicorp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License  
 * 
 */

 class mysql{
     
     private $campos=array();
     private $tabla="";
     
     private $consulta_grupo = NULL;
     private $grupo_array = array();
     protected $link = NULL;
     protected $archivo = "";
     
     private function show_error($mensaje,$linea,$die=false){
         if($die){
             die("<br /><b>Error Mysql</b>: ".$mensaje." en <b>".$_SERVER['PHP_SELF']."</b> en la linea <b>".$linea."</b><br />");
         }else{
             echo "<br /><b>Error Mysql</b>: ".$mensaje." en <b>".$_SERVER['PHP_SELF']."</b> en la linea <b>".$linea."</b><br />";
         }
     }
     
     /**
      * Construye un objeto del tipo mysql con mysqli
      * 
      * @param $archivo
      *
      * 
      */
     
     
     public function __construct($archivo){
     	 $this->archivo = $archivo;
         require_once $archivo;
         $this->link = mysqli_connect(HOSTSQL,USUARIOSQL,PASSSQL,DBSQL);
         if($this->link==false)
            funciones_generales::show_error_html('Error al establecer la conexión a la base de datos. <br />'.mysqli_error($this->link),true);
          
     }
     
     public function get_link(){
         return $this->link;
     }
     
     /**
      * 
      * Selecciona la base de datos, sin parámetros elije la base de datos predeterminada.
      * @param $nombre
      * 
      */
     
     public function select_bd($nombre=""){
         if(!defined(DBSQL))
            require_once $this->archivo;
         if($nombre==""){
             if(!mysqli_select_db($this->link,DBSQL))
                 funciones_generales::show_error_html("Error al seleccionar la base de datos.".mysqli_error($this->link),true);
         }else{
             if(!mysqli_select_db($this->link,$nombre)){
                 if(!mysqli_select_db($this->link,DBSQL)){
                    funciones_generales::show_error_html("Error al seleccionar la base de datos.".mysqli_error($this->link),true);
                 }else{
                     $this->show_error("No se ha podido seleccionar la tabla '".$nombre."'. Se ha seleccionado la tabla por defecto",(__LINE__)-4);
                 }
             }
         }
     }
     
     /**
      * Retorna el resultado del query mysql.
      * ATENCIÓN: Sanear las entradas del usuario.
      * 
      *  @param $sql
      *  @return mysql resource
      */
     
     public function query($sql){
         return mysqli_query($this->link,$sql);
     }
     
     /**
      * Retorna el último error mysql
      * 
      *  @param none
      *  @return mysql_error
      */
     
     public function error(){
         return mysqli_error($this->link);
     }
     
     /**
      * Retorna Saneado el String.
      * 
      *  @param $sql
      *  @return string
      */
     
     public function clean($string){
         return mysqli_real_escape_string($this->link,$string);
     }
     
     
     /**
      * Retorna el COUNT(*) de la tabla con la condicion proporcionada
      * ATENCIÓN: Sanear el String $condicion.
      * 
      *  @param $tabla,$condicion
      *  @return int
      */
     
     public function contar($tabla,$condicion){
     	 //echo "SELECT COUNT(*) FROM ".$tabla." ".$condicion;
         $row = mysqli_fetch_array($this->query("SELECT COUNT(*) FROM ".$tabla." ".$condicion),MYSQLI_NUM);
         return $row[0];
     }
     
     /**
      * Retorna el fetch de una consulta realizada anteriormente
      * Posibles valores para $mode
      * "array","assoc","row"
      * "array" por default
      * 
      *  @param $mysql resource, $mode
      *  @return array
      */
	 
	 public function fetch($mysql,$mode="array"){
	 	if($mode=="array"){
	 		return mysqli_fetch_array($mysql);
	 	}else if($mode=="assoc"){
	 		return mysqli_fetch_array($mysql,MYSQLI_ASSOC);
	 	}else if($mode=="row"){
	 		return mysqli_fetch_array($mysql,MYSQLI_NUM);
	 	}
	 }
     
     /**
      * Retorna un 'select' con los valores de la consulta enviada
      * 
      * 
      *  @param $mysql sentence,$nombre_select,$campo_valor,$campo_mostrar,$attr_select
      *  @return string
      */
     
     public function cargar_combo($sql,$nombre_select,$campo_valor,$campo_mostrar,$selected_value="",$attr_select=""){
         $resultado = "<select name='".$nombre_select."' ".$attr_select.">";
         $result = $this->query($sql);
         while($row = $this->fetch($result,"assoc")){
             $valor = "";
             if(htmlspecialchars($row[$campo_mostrar], ENT_QUOTES,'UTF-8')==""){
                 $valor = str_replace('"','/"',str_replace("'","/'",$row[$campo_mostrar]));
             }else{
                 $valor = htmlspecialchars($row[$campo_mostrar], ENT_QUOTES);
             }
             if($row[$campo_valor]==$selected_value){
                 $resultado .= '<option value="'.$row[$campo_valor].'" selected="selected">'.$valor.'</option>';
             }else{
                 $resultado .= '<option value="'.$row[$campo_valor].'">'.$valor.'</option>';
             }
         }
         return $resultado."</select>";
     }
     
     public function agregar_campo_consulta(){
         $args = func_get_args();
         $last = "";
         $c=0;
         foreach ($args as $value) {
             if($c%2!=0){
                 $this->campos[$last] = $value;
             }else{
                 $this->campos[$value] = "";
                 $last=$value;
             }
             $c++;
         }
     }
     
     public function agregar_tabla($tabla){
         $this->tabla = $tabla;
     }
     
     public function guardar_campos($condicion="",$mode=false,$erase_anyways=true){
         if($mode==false){
             $campo = array();
             $datos = array();
             foreach ($this->campos as $key => $value) {
                 array_push($campo,$key);
                 array_push($datos,$value);
             }
             $sql_text = "INSERT INTO ".$this->tabla."(";
             for($i=0; $i<count($campo);$i++) {
                 if($i!=0){
                     $sql_text .= ",";
                 }
                 $sql_text .= $campo[$i];
             }
             $sql_text .= ") VALUES(";
             for($i=0; $i<count($datos);$i++) {
                 if($i!=0){
                     $sql_text .= ",";
                 }
                 $sql_text .= "'".$this->clean($datos[$i])."'";
             }
             $sql_text .= ")";
         }else{
             $sql_text = "UPDATE ".$this->tabla." SET ";
             $c=0;
             foreach ($this->campos as $key => $value) {
                 if($c!=0){
                     $sql_text .= ",";
                 }
                 $sql_text .= $key."='".$this->clean($value)."'";
                 $c++;
             }
             $sql_text .= $condicion;
         }
         if($this->consulta_grupo==true && $mode==false){
             $this->grupo_array[] = array("tabla_modificacion_sistema"=>$this->tabla);
             $this->grupo_array[count($this->grupo_array)-1] = array_merge($this->grupo_array[count($this->grupo_array)-1],$this->campos);
         }
         $this->campos=array();
         $this->tabla = "";
         if($erase_anyways){
            $this->campos=array();$this->tabla = "";
         }
         if(!$this->query($sql_text))
             return false;
         
         $this->campos=array();
         $this->tabla = "";
         
         return true;
     }

    /**
     * Define un grupo de consultas en caso de haber un error al agregar las anteriores. Elimina todo nuevamente.
     * 
     */

    public function set_grupo_consultas(){
        $this->consulta_grupo = true;
    }
    
    /**
     * Termina el grupo de consultas.
     */
    
    public function end_grupo_consultas(){
        $this->consulta_grupo = NULL;
    }
    
    /**
     * Borra el grupo en caso de haber un error en la ejecución de alguna consulta MySQL
     */
    
    public function borrar_grupo(){
        for ($i=count($this->grupo_array)-2; $i>-1 ; $i--) {
            $sql_text = "DELETE FROM ".$this->grupo_array[$i]['tabla_modificacion_sistema']." WHERE ";
            $c=0;
            foreach ($this->grupo_array[$i] as $key => $value) {
                if($key!="tabla_modificacion_sistema"){
                    if($c!=0){
                        $sql_text .= " AND ";
                    }
                    $sql_text .= $key."='".$this->clean($value)."'";
                    $c++;
                }
            }
            if(!$this->query($sql_text))
                funciones_generales::show_error_html("Error borrando el grupo de consultas.".$this->error(),true);
            $this->consulta_grupo = NULL;
            $this->grupo_array = array();
        }
    }
    
    /**
     * Obtiene el valor de un campo X de una consulta realizada previamente.
     */
    
    public function get_campo_from_grupo($pos,$campo){
        $sql_text = "SELECT * FROM ".$this->grupo_array[$pos]['tabla_modificacion_sistema']." WHERE ";
        $c=0;
        foreach ($this->grupo_array[$pos] as $key => $value) {
            if($key!="tabla_modificacion_sistema"){
                if($c!=0){
                    $sql_text .= " AND ";
                }
                $sql_text .= $key."='".$this->clean($value)."'";
                $c++;
            }
        }
        $result = $this->fetch($this->query($sql_text));
        return $result[$campo];
    }
     
 }


?>
