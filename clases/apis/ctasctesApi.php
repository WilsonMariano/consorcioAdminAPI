<?php

class CtasCtesApi{
    
    public static function ProcessPayment($request, $response, $args){
        $apiParams = $request->getParsedBody();

        for($i = 0; $i < sizeof($apiParams["arrDeudas"]); $i++){
            $deuda = ($apiParams["arrDeudas"][$i]);

            $liquidacion = Funciones::GetOne($deuda["idLiquidacion"], Liquidaciones::class);
            Liquidaciones::UpdateSaldo($deuda["idLiquidacion"], $deuda["montoPagar"]);
        }
        // {
        //     "arrDeudas": [
        //       {
        //         "detalle": "LIQUIDACION EXPENSA PERIODO 12/19",
        //         "idLiquidacion": "2",
        //         "montoAsignado": "1320.00",
        //         "montoPagar": "1320.00"
        //       }
        //     ],
        //     "totalPagar": "1320"
        //  }
    }

    public static function GetDeudas ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        $listado= CtasCtes::GetDeudas($apiParams['idUF']);
		
		if($listado)
			return $response->withJson($listado, 200); 		
        else
            return $response->withJson(false, 400);
    }

}//class
