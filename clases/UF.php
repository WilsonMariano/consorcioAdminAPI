<?php

class UF{
	
	//	Atributos
	public $id;
	public $idManzana;
	public $idAdherente;
	public $idEdificio;
	public $nroUF;
	public $piso;
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
			$this->piso = $arrData['piso'];
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
		$consulta->bindValue(':idEdificio'  	,$objEntidad->idEdificio      ,\PDO::PARAM_INT);
		$consulta->bindValue(':piso'  	        ,$objEntidad->piso            ,\PDO::PARAM_INT);
		$consulta->bindValue(':codDepartamento' ,$objEntidad->codDepartamento ,\PDO::PARAM_STR);
		$consulta->bindValue(':codSitLegal'  	,$objEntidad->codSitLegal     ,\PDO::PARAM_STR);
		$consulta->bindValue(':coeficiente'  	,$objEntidad->coeficiente     ,\PDO::PARAM_STR);
		$consulta->bindValue(':codAlquila'   	,$objEntidad->codAlquila      ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de manzana a la cual pertenecen.
	 * Recibe por parámetro un idManzana (de la tabla Manzanas)
	 */
	public static function GetByNroManzana($nroManzana){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("select " . static::class. ".* from " . static::class. 
				" inner join Manzanas ma on UF.idManzana = ma.id where ma.nroManzana =:nroManzana");
			$consulta->bindValue(':nroManzana', $nroManzana , PDO::PARAM_INT);
			$consulta->execute();
			$arrObjEntidad= PDOHelper::FetchAll($consulta, static::class);	
			
			return $arrObjEntidad;
		
		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $nroManzana, $e);		 
			throw new ErrorException("No se pudieron recuperar las " . static::class . " para la manzana numero " . $nroManzana);
		}
	}

	/**
	 * Devuelve un array de objetos UF, buscando por el número de edificio al cual pertenecen.
	 * Recibe por parámetro un número de edificio que se preasume válido.
	 */
	public static function GetByManzanaAndEdificio($idManzana, $idEdificio){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class . 
				" where idManzana = :idManzana and idEdificio =:idEdificio");
			$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
			$consulta->bindValue(':idEdificio', $idEdificio , PDO::PARAM_INT);
			$consulta->execute();
			$arrObjEntidad = PDOHelper::FetchAll($consulta, static::class);	
			
			return $arrObjEntidad;

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idManzana, $e);		 
			throw new ErrorException("No se pudo recuperar la manzana de id" . $idManzana);
		}
	}

	/**
	 * Devuelve un objeto UF buscando por los campos idManzana y nroUF
	 */
	public static function GetByManzanaAndNumero($idManzana, $nroUF){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from " . static::class .
				" where idManzana = :idManzana and nroUF =:nroUF");
			$consulta->bindValue(':idManzana', $idManzana , PDO::PARAM_INT);
			$consulta->bindValue(':nroUF', $nroUF , PDO::PARAM_INT);
			$consulta->execute();
			$arrObjEntidad = PDOHelper::FetchObject($consulta, static::class);
			
			return $arrObjEntidad;
		
		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idManzana, $e);		 
			throw new ErrorException("No se pudo recuperar la manzana de id " . $idManzana);
		}
	}

}//class
