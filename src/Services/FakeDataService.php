<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 25/09/2019
 * Time: 12:37
 */

namespace App\Services;

use Faker;


class FakeDataService
{
    /**
     * Fake names generator
     * @var Faker\Generator
     */
    private $faker;

    /**
     * @var array
     * Array for further conversion into JSON
     */
    private $data_array = [];

    /**
     * Options for faker to create certain number of states and counties
     * @var array
     */
    private $options;

    /**
     * FakeDataService constructor.
     * @param array $options - available options to manage number of states and counties, tax rates and tax amounts
     */
    public function __construct(array $options = [])
    {
        $this->faker = Faker\Factory::create('en_US');
        $this->options = [
            'states_num'        => $options['states_num'] ?? 5,
            'min_counties'      => $options['min_counties'] ?? 3,
            'max_counties'      => $options['min_counties'] ?? 8,
            'min_tax_rate'      => $options['min_tax_rate'] ?? 0.2,
            'max_tax_rate'      => $options['max_tax_rate'] ?? 0.5,
            'min_tax_amount'    => $options['min_tax_amount'] ?? 100000,
            'max_tax_amount'    => $options['max_tax_amount'] ?? 1000000,
        ];
    }

    /**
     * Functions generates 2-dimensional array of states and corresponding counties
     * @return array
     */
    public function createDataArray():array
    {
        $result = [];
        for ($i = 1; $i <= $this->options['states_num']; $i++) {
            $result[] = [
                'name'      => $this->faker->state(),
                'counties'  => $this->createCounties()
            ];
        }
        return $result;
    }

    /**
     * Function creates children counties
     * @return array
     */
    private function createCounties():array
    {
        $result = [];
        $num = $this->faker->numberBetween($this->options['min_counties'], $this->options['max_counties']);

        for ($i = 1; $i <= $num; $i++) {
            $name       = $this->faker->city();
            $tax_rate   = $this->faker->randomFloat(2, $this->options['min_tax_rate'], $this->options['max_tax_rate']);
            $tax_amount = $this->faker->randomFloat(0, $this->options['min_tax_amount'], $this->options['max_tax_amount']);


            $result[] = [
                'name'          => $name,
                'tax_rate'      => $tax_rate,
                'tax_amount'    => $tax_amount
            ];
        }

        return $result;
    }

}