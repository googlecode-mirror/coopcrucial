<?php
require_once('CConexion.php');
class CAdministradores
{
	public	$hostname_conn;
	public	$database_conn;
	public	$username_conn;
	public  $password_conn;
	public  $conn ;
	
	
	//CONECTAR A LA BASE DE DATOS
	public function conectar()
	{
		
		$conexion = new CConexion();
		
		$this->hostname_conn = $conexion->obteneHostName();
		$this->database_conn = $conexion->obteneDataBase();
		$this->username_conn = $conexion->obteneUserName();
		$this->password_conn = $conexion->obtenePassword();
		$this->conn = mysql_pconnect($this->hostname_conn, $this->username_conn, $this->password_conn) or trigger_error(mysql_error(),E_USER_ERROR);

	}
	
	
	//Verificar nombre de usuario y contraseña
	public function verificarUsuario()
	{
		
		$this->conectar();
		
		$usuario = $_POST['usuario'];
		$password = $_POST['password'];
		$password = md5($password);
		
		mysql_select_db($this->database_conn, $this->conn);
		$sentencia = "SELECT * FROM `usuarioadministrador` WHERE `usuario` = '$usuario' AND `password` = '$password'";
	
		$resultado = mysql_query($sentencia,$this->conn)or die(mysql_error());
		$numFilas = mysql_num_rows($resultado);
		$fila = mysql_fetch_assoc($resultado);
		
		
		if($numFilas >0)
		{
			echo $msg =$fila[usuario]; 
		}
		else
		{
			echo  $msg ="No"; 
		}

	}	
	
	//Iniciar session de usuario admin
	public function iniciarSessionUsuario()
	{
		
		$usuario = $_POST['usuario'];
		session_start();
		if($usuario != "")
		{
			$_SESSION['usuario'] = $usuario;
			
		}
	}
	
	//Obtener session usuario
	public function obtenerSessionUsuario()
	{
		session_start();
		return $_SESSION['usuario'];
	}
	
	//Verificar si el usuario administrador esta autentificado
	public function autentificarSessionUsuario()
	{
		session_start();
		if($_SESSION['usuario'] !="")
		{
			
		}
		else
		{
			header("Location: index.php");
		}
	}
	
	//Cerrar session de usuario admin
	public function cerrarSessionUsuario()
	{

		session_start();
		session_destroy();
	}
	
	public function especificoDatosUsuario($usuario,$campo)
	{
		$this->conectar();
		
		
		mysql_select_db($this->database_conn, $this->conn);
		$sentencia = "SELECT * FROM `usuarioadministrador` WHERE `usuario` = '$usuario'";
	
		$resultado = mysql_query($sentencia,$this->conn)or die(mysql_error());
		$fila = mysql_fetch_assoc($resultado);
		return $fila[$campo];
	}
	
	public function modificarDatosUsuario()
	{
		$this->conectar();
		
		$usuario = $_POST['usuario'];
		$password = $_POST['password'];
		$password = md5($password);
		
				
		mysql_select_db($this->database_conn, $this->conn);
		$sentencia = "UPDATE `usuarioadministrador` SET `usuario` = '$usuario',
`password` = '$password' WHERE `usuarioadministrador`.`id` =1";
	
		$resultado = mysql_query($sentencia,$this->conn)or die(mysql_error());		
		
		if($resultado >0)
		{
			echo $msg ="Tus datos han sido modificados satisfactoriamente."; 
		}
		else
		{
			echo  $msg ="No"; 
		}		
	}
	
}


?>