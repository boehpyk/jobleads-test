<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 24/09/2019
 * Time: 16:00
 */

namespace App\Controllers;

use Slim\App;

class TaxesCountController
{
    /**
     * Data repository given in application container
     * @var $model
     */
    private $model;
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->model = $app->getContainer()['taxes_model'];
    }

    /**
     * Counts the overall amount of taxes collected per state
     * @return string - rendered twig template
     */
    public function overallAmountPerStateAction():string
    {
        $data = $this->model->getOverallAmountOfTaxes();
        return $this->app->getContainer()['twig']->render('per-state/amount.overall.html.twig', ['data' => $data]);
    }

    /**
     * Counts the average amount of taxes collected per state
     * @return string - rendered twig template
     */
    public function averageAmountPerStateAction():string
    {
        $data = $this->model->getAverageAmountOfTaxes();
        return $this->app->getContainer()['twig']->render('per-state/amount.average.html.twig', ['data' => $data]);
    }

    /**
     * Counts the average county tax rate per state
     * @return string - rendered twig template
     */
    public function averageTaxRatePerStateAction()
    {
        $data = $this->model->getAverageCountyTaxRate();
        return $this->app->getContainer()['twig']->render('per-state/tax-rate.average.html.twig', ['data' => $data]);
    }

    /**
     * Counts the average tax rate for the entire country
     * @return string - rendered twig template
     */
    public function averageCountryTaxRateAction()
    {
        $data = $this->model->getAverageCountryTaxRate();
        return $this->app->getContainer()['twig']->render('country/tax-rate.average.html.twig', ['data' => $data]);
    }

    /**
     * Counts the amount of taxes for the entire country
     * @return string - rendered twig template
     */
    public function overallAmountCountryAction()
    {
        $data = $this->model->getOverallCountryTaxes();
        return $this->app->getContainer()['twig']->render('country/amount.overall.html.twig', ['data' => $data]);
    }

}