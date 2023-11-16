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

$app->group('/Mesa', function (RouteCollectorProxy $group) {
    $group->get('/', \MesaController::class . ':TraerTodos');
    $group->get('/MasUsada', \MesaController::class . ':MesaMasUsada');
    $group->get('/Estado', \MesaController::class . ':TraerTodosEstado');
    $group->get('/{idMesa}', \MesaController::class . ':TraerUno');
    $group->post('/CargarMesa', \MesaController::class . ':CargarUno');
    $group->put('/ModificarMesa', \MesaController::class . ':ModificarUno')->add(new ModificarEstadoMesaMiddleware());
    $group->delete('/{idMesa}', \MesaController::class . ':BorrarUno');
  });

$app->group('/Pedido', function (RouteCollectorProxy $group) {
  $group->get('/Cliente', \PedidoController::class . ':TraerUnoCliente');
  $group->get('/', \PedidoController::class . ':TraerTodos');
  $group->get('/EstadoPedido', \PedidoController::class . ':TraerTodosSegunEstado');
  $group->get('/{codigoPedido}', \PedidoController::class . ':TraerUno');
  $group->post('/', \PedidoController::class . ':CargarUno');
  $group->put('[/]', \PedidoController::class . ':ModificarUno');
  $group->put('/ModificarEstadoEnPreparacion', \PedidoController::class . ':PedidoEnPreparacion')->add(new AuthMiddleware())->add(new AuthMozoMiddleware());
  $group->put('/ModificarEstadoListoParaServir', \PedidoController::class . ':PedidoListoParaServir')->add(new AuthMiddleware())->add(new AuthMozoMiddleware());
  $group->put('/ModificarCliente', \PedidoController::class . ':ModificarUnoCliente');
  $group->delete('/{codigoPedido}', \PedidoController::class . ':BorrarUno');
  $group->post('/SubirFoto', \fotoController::class . ':SubirFoto');
  
});

$app->group('/Producto', function (RouteCollectorProxy $group) {
  $group->get('[/]', \ProductosController::class . ':TraerTodos');
  $group->get('/TraerProductosEnPreparacion', \ProductosController::class . ':TraerTodosVerificado')->add(new AuthMiddleware());
  $group->get('/{idProducto}', \ProductosController::class . ':TraerUno');
  $group->post('[/]', \ProductosController::class . ':CargarUno');
  $group->put('[/]', \ProductosController::class . ':ModificarUno');
  $group->put('/ModificarEstadoEnPreparacion', \ProductosController::class . ':ModificarEstadoEnPreparacion')->add(new AuthMiddleware())->add(new ModificarEstadoProductoMiddleware());
  $group->put('/ModificarEstadoListoParaServir', \ProductosController::class . ':ModificarEstadoListoParaServir')->add(new AuthMiddleware())->add(new ModificarEstadoProductoMiddleware());
  $group->delete('/{idProducto}', \ProductosController::class . ':BorrarUno');
}); 

$app->group('/Usuario', function (RouteCollectorProxy $group) {
  $group->get('[/]', \UsuarioController::class . ':TraerTodos');
  $group->get('/{idUsuario}', \UsuarioController::class . ':TraerUno');
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->put('[/]', \UsuarioController::class . ':ModificarUno');
  $group->delete('/{idUsuario}', \UsuarioController::class . ':BorrarUno');
  $group->post('/Login', \UsuarioController::class . ':LoginUsuario');
});

$app->run();