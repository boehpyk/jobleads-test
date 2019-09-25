<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 25/09/2019
 * Time: 11:41
 */

namespace App\Models;


interface StatesModelInterface
{
    /**
     * Function creates records of states and their counties
     * @param array $data - array of input data
     */
    public function create(array $data):void ;
}