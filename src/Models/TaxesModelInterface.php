<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 24/09/2019
 * Time: 17:58
 */

namespace App\Models;


interface TaxesModelInterface
{
    /**
     * Function counts the overall amount of taxes collected per state
     * @return array
     */
    public function getOverallAmountOfTaxes():array;

    /**
     * Function counts the average amount of taxes collected per state
     * @return array
     */
    public function getAverageAmountOfTaxes():array;

    /**
     * Function counts the average taxes rate per state
     * @return array
     */
    public function getAverageCountyTaxRate():array;

    /**
     * Function counts the average taxes rate of the country
     * @return array
     */
    public function getAverageCountryTaxRate():array;

    /**
     * Function counts the collected overall taxes of the country
     * @return array
     */
    public function getOverallCountryTaxes():array;

}