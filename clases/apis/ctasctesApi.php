<?php

include_once __DIR__ . '/../CtasCtes.php';
include_once __DIR__ . '/../_FuncionesEntidades.php';

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        $apiParams = $request->getParsedBody();
    }

    /**
     * Genera un movimiento a favor del cliente, como si se hubiera generado una nota de crédito.
     */
	public static function CreditNote($request, $response, $args){
        $apiParams = $request->getParsedBody();

        $uf = UF::GetByNumero($apiParams['nroUF']);
        if($uf){
            $ctaCte = new CtasCtes();
            $ctaCte->idUF = $uf['id'];
            $ctaCte->fecha = date("Y-m-d");
            $ctaCte->descripcion = "NOTA DE CREDITO";
            $ctaCte->monto = $apiParams['monto'];
            $saldoActual = Helper::NumFormat(CtasCtes::GetLastSaldo($uf['id']) ?? 0);
            $ctaCte->saldo = $saldoActual + Helper::NumFormat($apiParams['monto']);

            $newId =  CtasCtes::Insert($ctaCte);
            if($newId < 1)
                throw new Exception("No se pudo actualizar uno de los movimientos en las cuentas corrientes.");
            else
                return $newId;
        }else{    
            throw new Exception("No se pudo actualizar uno de los movimientos en las cuentas corrientes.");
        }
    }

}//class
