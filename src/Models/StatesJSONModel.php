<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 25/09/2019
 * Time: 12:12
 */

namespace App\Models;

use Slim\App;

class StatesJSONModel implements StatesModelInterface
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function create(array $data): void
    {
        $filename = $this->app->getContainer()->get('settings')['json_datafile'];

        try {
            $fh = fopen($filename, "w");
            if (!$fh) {
                throw new \Exception("Could not open the JSON file for writing");
            }
            $json = json_encode($data, JSON_PRETTY_PRINT);
            fwrite($fh, $json);
            fclose($fh);
        } catch (\Exception $e) {
            echo "Error writing JSON into file: " . $e->getMessage();
        }
    }

}