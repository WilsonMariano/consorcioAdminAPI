<?php

include_once __DIR__ . '/../ConceptosGastos.php';

class ConceptoGastoApi{
    
    public static function Insert($request, $response, $args){
        $apiParams = $request->getParsedBody();

        $objConceptoGasto = new ConceptosGastos();
        $objConceptoGasto->codigo         = $apiParams['codigo'];
        $objConceptoGasto->conceptoGasto  = $apiParams['conceptoGasto'];

        //Valido que el código no esté duplicado antes de insertar
        if(!ConceptosGastos::IsDuplicated($objConceptoGasto))
            if(Funciones::InsertOne($objConceptoGasto))
                return $response->withJson(true, 200);
            else
                return $response->withJson(false, 500);
        else 
            return $response->withJson("El codigo de gasto ingresado no se encuentra disponible.", 400);
    }

    public static function GetOne ($request, $response, $args){
        $apiParams = $request->getQueryParams();

        $objEntidad = ConceptosGastos::GetOne($apiParams["codigo"]);
        
        if($objEntidad)
            return $response->withJson($objEntidad, 200); 
        else
            return $response->withJson(false, 400);  
    }

}//class
