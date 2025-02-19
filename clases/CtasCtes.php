<?php


class CtasCtes
{
	//Atributos
	public $id;
	public $idUF;
	public $idLiquidacion;
	public $fecha;
	public $descripcion;
	public $monto;
	public $saldo;

	//Constructor customizado
	public function __construct($arrData = null){
		if($arrData != null){
			$this->id            = $arrData["id"] ?? null;
			$this->idUF          = $arrData["idUF"];
			$this->idLiquidacion = $arrData["idLiquidacion"];
			$this->fecha         = $arrData["fecha"];
			$this->descripcion   = $arrData["descripcion"] ?? null;
			$this->monto         = $arrData["monto"];
			$this->saldo         = $arrData["saldo"];
		}
    }

	/**
	 * Bindeo los parametros para la consulta SQL.
	 */
	public function BindQueryParams($consulta,$objEntidad, $includePK = true){
		if($includePK == true)
			$consulta->bindValue(':id'		 ,$objEntidad->id       ,\PDO::PARAM_INT);
		
		$consulta->bindValue(':idUF'           ,$objEntidad->idUF           ,\PDO::PARAM_INT);
		$consulta->bindValue(':idLiquidacion'  ,$objEntidad->idLiquidacion  ,\PDO::PARAM_INT);
		$consulta->bindValue(':fecha'          ,$objEntidad->fecha          ,\PDO::PARAM_STR);
		$consulta->bindValue(':descripcion'    ,$objEntidad->descripcion    ,\PDO::PARAM_STR);
		$consulta->bindValue(':monto'          ,$objEntidad->monto          ,\PDO::PARAM_STR);
		$consulta->bindValue(':saldo'          ,$objEntidad->saldo          ,\PDO::PARAM_STR);
	}

	/**
	 * Devuelve el ultimo saldo calculado para una CtaCte.
	 * Recibe por parámetro el id de la unidad funcional
	 */
	public static function GetLastSaldo($idUF){
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		 
		$consulta = $objetoAccesoDato->RetornarConsulta("select saldo from " . static::class . 
			" where idUF = :idUF order by id desc limit 1");
		$consulta->bindValue(':idUF' , $idUF, \PDO::PARAM_INT);	
		$consulta->execute();

		return PDOHelper::FetchObject($consulta)->saldo ?? 0;
	}

    /**
     * Genera un movimiento a favor del cliente, simulando una nota de crédito.
     */
	public static function NewCreditNote($uf, $monto){
		try{		
			$ctaCte = new static();
			$ctaCte->idUF = $uf->id;
			$ctaCte->fecha = date("Y-m-d");
			$ctaCte->descripcion = "NOTA DE CREDITO";
			$ctaCte->monto = $monto;
			$saldoActual = NumHelper::NumFormat(self::GetLastSaldo($uf->nroUF) ?? 0);
			$ctaCte->saldo = $saldoActual + NumHelper::NumFormat($monto);

			$newId =  Funciones::InsertOne($ctaCte);
			return $newId;

			}catch(Exception $e){
				ErrorHelper::LogError(__FUNCTION__, $uf , $e);		 
				throw new ErrorException("No se pudo generar una nota de crédito para la uf " . $uf->id);
		}
	}
	
	public static function GetDeudas($idUF){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from vwDeudasUF where idUF = :idUF order by fechaEmision");
			$consulta->bindValue(':idUF', $idUF, \PDO::PARAM_INT);	
			$consulta->execute();

			return PDOHelper::FetchAll($consulta);

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idUF, $e);		 
			throw new ErrorException("No se pudieron recuperar las deudas de la uf " . $idUF);
		}
	}

	public static function GetMovimientos($idUF){
		try{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			
			$consulta = $objetoAccesoDato->RetornarConsulta("select * from CtasCtes where idUF = :idUF order by fecha desc");
			$consulta->bindValue(':idUF', $idUF, \PDO::PARAM_INT);	
			$consulta->execute();

			return PDOHelper::FetchAll($consulta);

		} catch(Exception $e){
			ErrorHelper::LogError(__FUNCTION__, $idUF, $e);		 
			throw new ErrorException("No se pudieron recuperar los movimientos de la uf " . $idUF);
		}
	}

}//class