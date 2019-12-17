<?php

require_once "AccesoDatos.php";

class UF{
	
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $idEdificio;
	public $nroUF;
	public $codDepartamento;
	public $codSitLegal;
	public $coeficiente;
	public $codAlquila;

	public function __construct($arrData = null){
		if($arrData != null){
			$this->id = $arrData['id'] ?? null;
			$this->idManzana = $arrData['idManzana'];
			$this->idAdherente = $arrData['idAdherente'];
			$this->nroUF = $arrData['nroUF'] ?? null;
			$this->idEdificio = $arrData['idEdificio'] ?? null;
			$this->codDepartamento = $arrData['codDepartamento'] ?? null;
			$this->codSitLegal = $arrData['codSitLegal'];
			$this->coeficiente = $arrData['coeficiente'];
			$this->codAlquila = $arrData['codAlquila'];
		}
	}

	/**
	* Bindeo los parametros para la consulta SQL.
	*/
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){

		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idManzana'    	,$objEntidad->idManzana       ,\PDO::PARAM_INT);
		$consulta->bindValue(':idAdherente'  	,$objEntidad->idAdherente     ,\PDO::PARAM_INT);
		$consulta->bindValue(':nroUF'  	        ,$objEntidad->nroUF           ,\PDO::PARAM_INT);
		$consulta->bindValue(':idEdificio'  	,$objEntidad->idEdificio     ,\PDO::PARAM_INT);
		$consulta->bindValue(':codDepartamento' ,$objEntidad->codDepartamento ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  	,$objEntidad->codSitLegal     ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  	,$objEntidad->coeficiente     ,\PDO::PARAM_STR);
		$consulta->bindValue(':codAlquila'   	,$objEntidad->codAlquila      ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de manzana a la cual pertenecen.
	 * Recibe por parámetro un idManzana (de la tabla Manzanas)
	 */
	public static function GetByManzana($idManzana){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where idManzana =:idManzana");
		$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de edificio al cual pertenecen.
	 * Recibe por parámetro un número de edificio que se preasume válido.
	 */
	public static function GetByEdificio($idManzana, $idEdificio){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"select * from UF where idManzana = :idManzana and idEdificio =:idEdificio");
		$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
		$consulta->bindValue(':idEdificio', $idEdificio , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetchAll(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	/**
	 * Devuelve un objeto UF buscando por el campo nroUF
	 */
	public static function GetByNumero($idManzana, $nroUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta(
			"select * from UF where idManzana = :idManzana and nroUF =:nroUF");
		$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
		$consulta->bindValue(':nroUF', $nroUF , PDO::PARAM_INT);
		$consulta->execute();
		$arrObjEntidad= $consulta->fetch(PDO::FETCH_ASSOC);	
		
		return $arrObjEntidad;					
	}

	//TODO: revisar esta funcion, no deberia incluir el nro de manzana ?
	public static function IsDuplicated($nroUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		
		$consulta =$objetoAccesoDato->RetornarConsulta("select * from UF where nroUF =:nroUF");
		$consulta->bindValue(':nroUF', $nroUF, PDO::PARAM_INT);
		$consulta->execute();
		
		return $consulta->rowCount() > 0 ? true : false;
	}

}//class
