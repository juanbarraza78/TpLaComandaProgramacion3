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

require_once './middlewares/LoggerbartenderMiddleware.php';
require_once './middlewares/LoggerCerbeceroMiddleware.php';
require_once './middlewares/LoggerClienteMiddleware.php';
require_once './middlewares/LoggerCocineroMiddleware.php';
require_once './middlewares/LoggerMozoMiddleware.php';
require_once './middlewares/ModificarEstadoMesaMiddleware.php';


require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/UsuarioController.php';

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
$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno');
    $group->post('/CargarMesa', \MesaController::class . ':CargarUno');
    $group->put('/ModificarMesa', \MesaController::class . ':ModificarUno')->add(new ModificarEstadoMesaMiddleware());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno');
  });


$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/{clave}', \PedidoController::class . ':TraerUno');
  $group->post('/', \PedidoController::class . ':CargarUno')->add(new LoggerMozoMiddleware());
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->put('/ModificarEstado', \PedidoController::class . ':ModificarEstado');
  $group->delete('/{codigoPedido}', \PedidoController::class . ':BorrarUno');
  $group->post('/FinalizarPedido', \PedidoController::class . ':FinalizarPedido');
});

$app->group('/Producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductosController::class . ':TraerTodos');
  $group->get('/{idProducto}', \ProductosController::class . ':TraerUno');
  $group->post('[/]', \ProductosController::class . ':CargarUno');
  $group->put('[/]', \ProductosController::class . ':ModificarUno');
  $group->put('/ModificarEstado', \ProductosController::class . ':ModificarEstado');
  $group->delete('/{codigoPedido}', \ProductosController::class . ':BorrarUno');
}); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{idUsuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{idUsuario}', \UsuarioController::class . ':BorrarUno');
}); 

$app->run();