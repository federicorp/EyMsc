<?php
/**   Archivo de conexión a la base de datos
  *   @name Configuraciones de conexión de Base de Datos
  *   @package classPHP
  *   @author Federicorp
  *   @copyright 2013 Federicorp
  *   @license http://opensource.org/licenses/gpl-license.php GNU Public License
  *
  *	  Antes de tocar este archivo, asegurece de saber los conceptos de Base de Datos y su conexión.
  *   
  */
  

  /*  En las lineas siguientes podrá modificar las configuraciones de conexión. 
   *  Debe modificar siempre la palabra situada luego de la coma ( , )
   *  Al modificar la palabra antes de la coma ( , ) puede enfrentar serios problemas de código.
   *  Ejemplos:
   *    define( "HOSTSQL" ,    "miservidor.com"   );           <--- Correcto
   *    define( "miservidor.com"" ,    "miservidor.com"   );   <--- Incorrecto
   */
   
   
  /**   
    *   Servidor de la Base de datos.
    */   
  define( "HOSTSQL" ,    "127.0.0.1"   ); //En algunos casos no hace falta tocar esta línea
  
  /**  
    *   Nombre de la Base de datos a ser utilizada.
    */ 
  define( "DBSQL" ,    "ideas_prueba"   );
  
  /**  
    *   Usuario para acceder a la base de datos.
    */ 
  define( "USUARIOSQL" ,    "root"   );
  
  /** 
    *   Contraseña para el usuario de la base de datos.
    */ 
  define( "PASSSQL" ,    ""   );
  
  /**  
    *   En caso que la Base de datos sea SQLite.
    */ 
  define( "SQLITE" ,    false   );
  
  /**  
    *   Direccion BD
    */ 
  define( "DIRSQLITE" ,    ""   );
  
?>