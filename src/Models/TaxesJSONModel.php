<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 25/09/2019
 * Time: 09:47
 */

namespace App\Models;


use Slim\App;

class TaxesJSONModel implements TaxesModelInterface
{
    private $data = [];

    public function __construct(App $app)
    {
        // retrieve path to JSON file from settings
        $datafile = $app->getContainer()->get('settings')['json_datafile'];

        // get the array of data
        $this->data = $this->getJSONData($datafile);
    }

    public function getOverallAmountOfTaxes(): array
    {
        return array_map(function($item) {
            return [
                'name'      => $item['name'],
                'amount'    => array_reduce($item['counties'], function($amount, $county) {
                    return $amount += $county['tax_amount'];
                }, 0)
            ];
        }, $this->data);
    }

    public function getAverageAmountOfTaxes(): array
    {
        return array_map(function($item) {
            return [
                'name'      => $item['name'],
                'amount'    => (array_reduce($item['counties'], function($amount, $county) {
                    return $amount += $county['tax_amount'];
                }, 0)) / count($item['counties'])
            ];
        }, $this->data);
    }

    public function getAverageCountyTaxRate(): array
    {
        return array_map(function($item) {
            return [
                'name'      => $item['name'],
                'tax_rate'  => (array_reduce($item['counties'], function($amount, $county) {
                        return $amount += $county['tax_rate'];
                    }, 0)) / count($item['counties'])
            ];
        }, $this->data);
    }


    public function getAverageCountryTaxRate(): array
    {
        $counties = call_user_func_array('array_merge', array_map(function ($item) {
            return $item['counties'];
        }, $this->data));

        return [
            'tax_rate' => (array_reduce($counties, function($tax_rate, $item) {
                return $tax_rate += $item['tax_rate'];
            }, 0)) / count($counties)
        ];
    }


    public function getOverallCountryTaxes(): array
    {
        $counties = call_user_func_array('array_merge', array_map(function ($item) {
            return $item['counties'];
        }, $this->data));

        return [
            'amount' => array_reduce($counties, function($amount, $item) {
                    return $amount += $item['tax_amount'];
                }, 0)
        ];
    }

    /**
     * Function reads JSON file and converts it into array
     * @param string $path - path to JSON file
     * @return array
     */
    private function getJSONData(string $path):array
    {
        $content = file_get_contents($path);
        if ($content !== false) {
            return json_decode($content, true);
        }
    }
}