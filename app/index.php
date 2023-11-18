<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './db/AccesoDatos.php';

require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/fotoController.php';

require_once './middlewares/AuthMiddleware.php';
require_once './middlewares/AuthMozoMiddleware.php';
require_once './middlewares/AuthSocioMiddleware.php';
require_once './middlewares/ModificarEstadoProductoMiddleware.php';
require_once './middlewares/ModificarEstadoMesaMiddleware.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Set base path
//$app->setBasePath('/app');

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// ->add(new AuthMiddleware())
// ->add(new AuthMozoMiddleware())
// ->add(new AuthSocioMiddleware())
// ->add(new ModificarEstadoProductoMiddleware())
// ->add(new ModificarEstadoMesaMiddleware())

$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('/', \MesaController::class . ':TraerTodos')->add(new AuthSocioMiddleware());
    $group->get('/MasUsada', \MesaController::class . ':MesaMasUsada')->add(new AuthSocioMiddleware());
    $group->get('/MejoresComentarios', \MesaController::class . ':MesaMejoresComentarios')->add(new AuthSocioMiddleware());
    $group->get('/Estado', \MesaController::class . ':TraerTodosEstado')->add(new AuthSocioMiddleware());
    $group->get('/GuardarCsv', \MesaController::class . ':GuardarCSV');
    $group->get('/CargarCsv', \MesaController::class . ':CargarCSV');
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno')->add(new AuthSocioMiddleware());
    $group->post('/CargarMesa', \MesaController::class . ':CargarUno')->add(new AuthSocioMiddleware());
    $group->put('/ModificarMesa', \MesaController::class . ':ModificarUno')->add(new ModificarEstadoMesaMiddleware());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno')->add(new AuthSocioMiddleware());
  })->add(new AuthMiddleware());

$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('/', \PedidoController::class . ':TraerTodos');
  $group->get('/EstadoPedido', \PedidoController::class . ':TraerTodosSegunEstado');
  $group->get('/GuardarCsv', \PedidoController::class . ':GuardarCSV');
  $group->get('/CargarCsv', \PedidoController::class . ':CargarCSV');
  $group->get('/{codigoPedido}', \PedidoController::class . ':TraerUno');
  $group->post('/', \PedidoController::class . ':CargarUno');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->put('/ModificarEstadoEnPreparacion', \PedidoController::class . ':PedidoEnPreparacion');
  $group->put('/ModificarEstadoListoParaServir', \PedidoController::class . ':PedidoListoParaServir');
  $group->delete('/{codigoPedido}', \PedidoController::class . ':BorrarUno');
  $group->post('/SubirFoto', \fotoController::class . ':SubirFoto');  
})->add(new AuthMozoMiddleware())->add(new AuthMiddleware());

$app->group('/Producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductosController::class . ':TraerTodos')->add(new AuthMozoMiddleware());
  $group->get('/TraerProductosEnPreparacion', \ProductosController::class . ':TraerTodosVerificado');
  $group->get('/GuardarCsv', \ProductosController::class . ':GuardarCSV');
  $group->get('/CargarCsv', \ProductosController::class . ':CargarCSV');
  $group->get('/{idProducto}', \ProductosController::class . ':TraerUno')->add(new AuthMozoMiddleware());
  $group->post('[/]', \ProductosController::class . ':CargarUno')->add(new AuthMozoMiddleware());
  $group->put('[/]', \ProductosController::class . ':ModificarUno')->add(new AuthMozoMiddleware());
  $group->put('/ModificarEstadoEnPreparacion', \ProductosController::class . ':ModificarEstadoEnPreparacion')->add(new ModificarEstadoProductoMiddleware());
  $group->put('/ModificarEstadoListoParaServir', \ProductosController::class . ':ModificarEstadoListoParaServir')->add(new ModificarEstadoProductoMiddleware());
  $group->delete('/{idProducto}', \ProductosController::class . ':BorrarUno')->add(new AuthMozoMiddleware());
})->add(new AuthMiddleware()); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos')->add(new AuthSocioMiddleware())->add(new AuthMiddleware());
  $group->get('/GuardarCsv', \UsuarioController::class . ':GuardarCSV');
  $group->get('/CargarCsv', \UsuarioController::class . ':CargarCSV');
  $group->get('/{idUsuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{idUsuario}', \UsuarioController::class . ':BorrarUno');
})->add(new AuthSocioMiddleware())->add(new AuthMiddleware());

$app->group('/Cliente', function (RouteCollectorProxy $group) {
  $group->get('/TraerUno', \PedidoController::class . ':TraerUnoCliente');
  $group->put('/Modificar', \PedidoController::class . ':ModificarUnoCliente');
});

$app->post('/Login', \UsuarioController::class . ':LoginUsuario');

$app->run();