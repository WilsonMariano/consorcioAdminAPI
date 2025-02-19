<?php

    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once 'vendor/autoload.php';
	require_once 'clases/_AccesoDatos.php';
	require_once 'clases/PdfGenerator.php';
	
	//Incluir todas las apis creadas
	foreach (glob("clases/apis/*.php") as $filename){
		require_once $filename;
	}
      
    $config['displayErrorDetails'] = true;
    $config['addContentLengthHeader'] = false;

    $app = new \Slim\App(["settings" => $config]);


    $app->add(function ($req, $res, $next){
		$response = $next($req, $res);
		return $response
		->withHeader('Access-Control-Allow-Origin', 'http://localhost:4200')
		->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
		->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	});


	//Test method
	$app->get('/ok', function (Request $request, Response $response, $args) {
		$response->getBody()->write("OK", 200);
		return $response;
	});


	//Generic
    $app->group('/generic', function () {
		$this->post('/post[/]', \GenericApi::class . ':Insert');
		$this->get('/all[/]', \GenericApi::class . ':GetAll');      
        $this->put('/put[/]', \GenericApi::class . ':UpdateOne');      
        $this->delete('/del/{id}', \GenericApi::class . ':DeleteOne');

		$this->get('/paged[/]', \GenericApi::class . ':GetPagedWithOptionalFilter');         
		$this->get('/one/{id}', \GenericApi::class . ':GetOne');    
		$this->get('/is-duplicated/{id}', \GenericApi::class . ':IsDuplicated');    
    });


	// *********************************************************************************
	// ********************  FUNCIONES AGRUPADAS POR ENTIDAD ***************************
	// *********************************************************************************

	$app->group('/adherentes', function () {
		$this->post('/insert[/]', \AdherenteApi::class . ':Insert');      
	});

	$app->group('/concepto-gasto', function () {
		$this->get('/one[/]'     , \ConceptoGastoApi::class . ':GetOne'); 
		$this->post('/insert[/]' , \ConceptoGastoApi::class . ':Insert');      
	});

	$app->group('/ctas-ctes', function () {
		$this->post('/payment[/]' , \CtasCtesApi::class . ':ProcessPayment');      
		$this->get('/getDeudas[/]' , \CtasCtesApi::class . ':GetDeudas');      
		$this->get('/getMovimientos[/]' , \CtasCtesApi::class . ':GetMovimientos');      
	});

	$app->group('/diccionario', function () {
		$this->get('/getValue[/]', \DiccionarioApi::class . ':GetValue');          
		$this->get('/getAll[/]', \DiccionarioApi::class . ':GetAll');          
	});
	
	$app->group('/expensa', function () {
		$this->post('/process[/]', \ExpensaApi::class . ':ProcessExpenses');  
	});

	$app->group('/gastos-liq', function () {
		$this->post('/insert[/]', \GastoLiquidacionApi::class . ':Insert');  
		$this->delete('/del[/]', \GastoLiquidacionApi::class . ':Delete');        
	});

	$app->group('/liquidacion-gbl', function () {
		$this->post('/insert[/]', \LiquidacionGlobalApi::class . ':Insert');          
	});

	$app->group('/notas-credito-debito', function () {
		$this->post('/new[/]', \notaCreditoDebitoApi::class . ':New');  
	});

	$app->group('/uf', function () { 
		$this->post('/insert[/]',  \UFApi::class . ':Insert');      
		$this->get('/GetByManzanaAndNumero[/]',  \UFApi::class . ':GetByManzanaAndNumero');      
	});
	
	$app->group('/usuarios', function () {
		$this->post('/login[/]', \UsuarioApi::class . ':Login');      
	});

	$app->get('/pdf', function (Request $request, Response $response, array $args) {

		PdfGenerator::generateRecibo($response);
	});

	$app->get('/phpinfo', function (Request $request, Response $response, array $args) {
		$response->getBody()->write(phpinfo());
		return $response;
	});


	$app->run();