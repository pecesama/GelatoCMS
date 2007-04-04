<?php
/**
 * version 0.0.1
 *
 * Clase Conexion_Mysql
 *
 * @name Manejo de la conexion a la BD.
 * @version 0.0.1
 * @link http://www.pecesama.net/weblog/
 * @copyright MIT Licence
 * @author Pedro Santana [pecesama]
 */

// constantes
define('MYSQL_TYPES_NUMERIC', 'int real ');
define('MYSQL_TYPES_DATE', 'datetime timestamp year date time ');
define('MYSQL_TYPES_STRING', 'string blob '); 

class Conexion_Mysql {

	var $mbase_datos;	
	var $mservidor;	
	var $musuario;	
	var $mclave; 
	var $mid_conexion = 0; 	// Identificador de conexión	
	var $mid_consulta = 0; 	// Identificador de consulta
	var $merror_numero = 0;		// Número de error			
	var $merror = "";		// Descripción del error.
	
	/** Al crear una instancia de clase, se ejecutará esta función */	
	function Conexion_Mysql($bd="", $host="localhost", $user="", $pass="") {	
		$this->mbase_datos = $bd;	
		$this->mservidor = $host;	
		$this->musuario = $user;	
		$this->mclave = $pass;
		
		$this->conectar();	
	}
	
	/** Conectar a la base de datos */	
	function conectar() {		
		// Conectamos al servidor		
		$this->mid_conexion = mysql_connect($this->mservidor, $this->musuario, $this->mclave);		
		if (!$this->mid_conexion) {		
			$this->merror = "No se logró realizar la conexión.";		
			return false;
		}	 
		//seleccionamos la base de datos		
		if (!@mysql_select_db($this->mbase_datos, $this->mid_conexion)) {		
			$this->merror = "No se puede abrir la base ".$this->mbase_datos ;		
			return false;		
		}	 
		return $this->mid_conexion;	// Si todo salio bien regresa el id de la conexión
	}	
	
	/** Para ejecutar consultas en la conexión abierta */	
	function ejecutarConsulta($msql = "") {
		if ($msql == "") {	
			$this->merror = "No introdujo la sentencia SQL";	
			return false;	
		}		
		//ejecutamos la consulta		
		$this->mid_consulta = mysql_query($msql, $this->mid_conexion);		
		if (!$this->mid_consulta) {		
			$this->merror_numero = mysql_errno();		
			$this->merror = mysql_error()." error";
			return false;		
		}				
		return $this->mid_consulta; // Si todo salio bien regresa el id de la consulta	
	}	
	
	/**
	 * Inserta un registro en la DB por cada llave->valor en un arreglo.
	 * No se debe usar sentencias SQL con esta funcion.
	 * Para usar sentencias SQL se debe utilizar ejecutarConsulta().
	 *
	 * @param mixed $tabla El nombre de la tabla en la BD.
	 * @param array $datos Arreglo con los campos que se desean insertar $arreglo['campo'] = 'valor'.
	 * @return string El ID del insert, verdadero si la tabla no tiene un campo auto_increment o false si ocurre un error.
	 */
	function insertarDeFormulario($tabla, $datos) {
	  	  
	  if (empty($datos)) {
		 $this->merror = "Debes de pasar un arreglo como parametro.";
		 return false;
	  }
	  
	  $cols = '(';
	  $sqlValues = '(';
	  
	  foreach ($datos as $llave=>$valor) {
		  
		 $cols .= "$llave,";  
		 
		 $tipo_col = $this->obtenerTipoCampo($tabla, $llave);  // obtiene el tipo de campo
		 if (!$tipo_col) return false;  // error!
		 
		 // determina si se necesita poner comillas al valor.
		 if (is_null($valor)) {
			$sqlValues .= "NULL,";   
		 } 
		 elseif (substr_count(MYSQL_TYPES_NUMERIC, "$tipo_col ")) {
			$sqlValues .= "$valor,";
		 }
		 elseif (substr_count(MYSQL_TYPES_DATE, "$tipo_col ")) {
			$valor = $this->formatearFecha($valor, $tipo_col); // formatea las fechas
			$sqlValues .= "'$valor',";
		 }
		 elseif (substr_count(MYSQL_TYPES_STRING, "$tipo_col ")) {
			$valor = addslashes($valor);
			$sqlValues .= "'$valor',";  
		 }
	  }
	  $cols = rtrim($cols, ',').')';
	  $sqlValues = rtrim($sqlValues, ',').')';     
	  
	  // inserta los valores en la DB	  
	  $sql = "INSERT INTO $tabla $cols VALUES $sqlValues";	  
	  return $this->ejecutarConsulta($sql);	  
	}
	
	/**
	 * Modifica un registro en la DB por cada llave->valor en un arreglo.
	 * No se debe usar sentencias SQL con esta funcion.
	 * Para usar sentencias SQL se debe utilizar ejecutarConsulta().
	 *
	 * @param mixed $tabla El nombre de la tabla en la BD.
	 * @param array $datos Arreglo con los campos que se desean insertar $arreglo['campo'] = 'valor'.
	 * @param mixed $condicion Es basicame una clausula WHERE (sin el WHERE). Por ejemplo,
	 * 		"columna=valor AND columna2='otro valor'" seria una condicion.
	 * @return string El numero de registros afectados o verdadero si no necesitaba actualizarse el registro.
	 * 		Falso si ocurrio algun error.
	 */
	function modificarDeFormulario($tabla, $datos, $condicion="") {
      
		if (empty($datos)) {
			$this->merror = "Debes de pasar un arreglo como parametro.";
			return false;
		}
		  
		$sql = "UPDATE $tabla SET";
		foreach ($datos as $llave=>$valor) {
			$sql .= " $llave=";
			
			$tipo_col = $this->obtenerTipoCampo($tabla, $llave);  // obtiene el tipo de campo
			if (!$tipo_col) return false;  // error!
			
			// determina si se necesita poner comillas al valor.
			if (is_null($valor)) {
			$sql .= "NULL,";   
			} 
			elseif (substr_count(MYSQL_TYPES_NUMERIC, "$tipo_col ")) {
			$sql .= "$valor,";
			}
			elseif (substr_count(MYSQL_TYPES_DATE, "$tipo_col ")) {
			$valor = $this->sql_date_format($valor, $tipo_col); /// formatea las fechas
			$sql .= "'$valor',";
			}
			elseif (substr_count(MYSQL_TYPES_STRING, "$tipo_col ")) {
			if ($this->auto_slashes) $valor = addslashes($valor);
			$sql .= "'$valor',";  
			}	
		}
		$sql = rtrim($sql, ','); // elimina la ultima coma
		if (!empty($condicion)) $sql .= " WHERE $condicion";
		
		// modifica los valores
		return $this->ejecutarConsulta($sql);
	}
	
	/**
	 * Obtiene la informacion sobre un campo usando la funcion mysql_fetch_field.	 
	 *
	 * @param mixed $tabla El nombre de la tabla en la BD.
	 * @param string $campo El campo del que se desea la informacion.
	 * @return array Un arreglo con la informacion del campo o false si hay algun error.
	 */
	function obtenerTipoCampo($tabla, $campo) {
	
	  $r = mysql_query("SELECT $campo FROM $tabla");
	  if (!$r) {
		 $this->merror = mysql_error();
		 return false;
	  }
	  $ret = mysql_field_type($r, 0);
	  if (!$ret) {
		 $this->merror = "No se puede obtener la informacion del campo ".$tabla.$campo.".";
		 mysql_free_result($r);
		 return false;
	  }
	  mysql_free_result($r);
	  return $ret;	  
	}
   
	/**
	 * Convierte una fecha en formato para DB.
	 *
	 * @param mixed $valor Se le puede pasar un valor timestamp como time() o un string como '04/14/2003 5:13 AM'.
	 * @return date Fecha para insertar en la BD.
	 */
	function formatearFecha($valor) {
	  
	  if (gettype($valor) == 'string') $valor = strtotime($valor);
	  return date('Y-m-d H:i:s', $valor);
	
	}
	
	/**
	 * Obtiene el registro obtenido de una consulta.
	 */
	function obtenerRegistro() {				
		return mysql_fetch_array($this->mid_consulta);	  	
	}	
	
	/**
	 * Devuelve el número de campos de una consulta.
	 */
	function contarCampos() {	
		return mysql_num_fields($this->mid_consulta);	
	}	
	
	/**
	 * Devuelve el número de registros de una consulta.
	 */
	function contarRegistros() {	
		return @mysql_num_rows($this->mid_consulta);	
	}	
	
	/**
	 * Devuelve el nombre de un campo de una consulta.
	 */
	function obtenerNombreCampo($numero_campo) {	
		return mysql_field_name($this->mid_consulta, $numero_campo);	
	}	
	
	/**
	 * Muestra los datos de una consulta (para debug).
	 */
	function verConsulta() {
		echo "<table border=1>\n";	 	
		// mostramos los nombres de los campos		
		for ($i = 0; $i < $this->contarCampos(); $i++) {		
			echo "<td><b>".$this->obtenerNombreCampo($i)."</b></td>\n";		
		}		
		echo "</tr>\n";		
		// mostrarmos los registros
		while ($row = mysql_fetch_row($this->mid_consulta)) {		
			echo "<tr> \n";		
			for ($i = 0; $i < $this->contarCampos(); $i++) {		
				echo "<td>".$row[$i]."</td>\n";		
			}		
			echo "</tr>\n";		
		}	
	}
	
	/**
	 * Cierra la conexion a la BD.
	 */
	function cierraConexion() {
		mysql_free_result($this->mid_consulta);
	}
	
} //fin de la Clase conexion_mysql
?>