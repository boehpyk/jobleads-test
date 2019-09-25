<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\TaxesSQLModel;
use App\Models\TaxesJSONModel;

require '../vendor/autoload.php';

$config = [
    'settings' => [
        'displayErrorDetails'   => true,
        'json_datafile'         => __DIR__ . '/../data/data.json',
    ],
];

$app = new \Slim\App($config);
$container = $app->getContainer();

/**
 * Database connection
 * @param $c
 * @return PDO
 */
$container['db'] = function ($c) {
    try {
        $pdo = new PDO('sqlite:../data/data.sqlite3');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
    catch (PDOException $e) {
        echo $e->getmessage();
    }
};

/**
 * Twig template service
 * @param $c
 * @return \Twig\Environment
 */
$container['twig'] = function ($c) {
    $loader = new \Twig\Loader\FilesystemLoader('../src/Views');
    return new \Twig\Environment($loader, [
//        'cache' => '../cache',
            'cache' => false,
    ]);
};

/**
 * Service for generating fake data for states and counties.
  * @param $c
 * @return \App\Services\FakeDataService
 */
$container['faker_service'] = function ($c) use($app) {
    return new \App\Services\FakeDataService([
        'states_num'        => 5,
        'min_counties'      => 3,
        'max_counties'      => 8,
        'min_tax_rate'      => 0.1,
        'max_tax_rate'      => 0.5,
        'min_tax_amount'    => 200000,
        'max_tax_amount'    => 2000000
    ]);
};


/**
 * Container to save required model depending on data source.
 * Here it's possible to switch between data sources
 * @param $c
 * @return \App\Models\TaxesModelInterface
 */
$container['taxes_model'] = function ($c) use($app) {
//    return new TaxesSQLModel($app); // SQLite data source
    return new TaxesJSONModel($app); // JSON data source
};

/**
 * Routes
 */
$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    return $app->getContainer()['twig']->render('index.html.twig');
});

$app->get('/fixtures/load', function (Request $request, Response $response, array $args) use ($app) {
    $load = new \App\Controllers\FixturesController($app);
    return $load->showForm();
});

$app->post('/fixtures/load', function (Request $request, Response $response, array $args) use ($app) {
    $load = new \App\Controllers\FixturesController($app);
    return $load->loadDataAction();
});

$app->get('/per-state/amount/overall', function(Request $request, Response $response, array $args) use ($app) {
    $obj = new \App\Controllers\TaxesCountController($app);
    return $obj->overallAmountPerStateAction();
});

$app->get('/per-state/amount/average', function(Request $request, Response $response, array $args) use ($app) {
    $obj = new \App\Controllers\TaxesCountController($app);
    return $obj->averageAmountPerStateAction();
});

$app->get('/per-state/tax-rate/average', function(Request $request, Response $response, array $args) use ($app) {
    $obj = new \App\Controllers\TaxesCountController($app);
    return $obj->averageTaxRatePerStateAction();
});
$app->get('/country/tax-rate/average', function(Request $request, Response $response, array $args) use ($app) {
    $obj = new \App\Controllers\TaxesCountController($app);
    return $obj->averageCountryTaxRateAction();
});
$app->get('/country/amount/overall', function(Request $request, Response $response, array $args) use ($app) {
    $obj = new \App\Controllers\TaxesCountController($app);
    return $obj->overallAmountCountryAction();
});


$app->run();