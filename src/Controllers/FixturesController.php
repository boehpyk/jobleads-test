<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 23/09/2019
 * Time: 15:14
 */

namespace App\Controllers;

use Slim\App;
use App\Models\StatesSQLModel;
use App\Models\StatesJSONModel;

class FixturesController
{
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * Function simple shows button for refresh data
     * @return string
     */
    public function showForm():string
    {
        return $this->app->getContainer()['twig']->render('fixtures/load_form.html.twig');
    }

    /**
     * Load states into database
     * @return string
     */
    public function loadDataAction():string
    {
        $data_array = $this->app->getContainer()->get('faker_service')->createDataArray();

        $sql_model = new StatesSQLModel($this->app);
        $sql_model->create($data_array);

        $json_model = new StatesJSONModel($this->app);
        $json_model->create($data_array);

        return $this->app->getContainer()['twig']->render('fixtures/loaded.html.twig', ['message' => 'All data fixtures loaded successfully!']);
    }

}