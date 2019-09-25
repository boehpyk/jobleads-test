<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 25/09/2019
 * Time: 11:43
 */

namespace App\Models;


use Slim\App;

class StatesSQLModel implements StatesModelInterface
{
    private $app;
    private $db;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->db = $app->getContainer()['db'];
    }

    public function create(array $data): void
    {
        $this->createStatesSQLTables();
        $this->createCountiesSQLTables();
        $this->insertStates($data);
    }

    /**
     * creates SQL table for states
     * @return void
     */
    private function createStatesSQLTables()
    {
        $this->db->exec("DROP TABLE IF EXISTS states");
        $this->db->exec("CREATE TABLE states(
                                  id INTEGER PRIMARY KEY, 
                                  name VARCHAR(32) 
                                )");
    }

    /**
     * creates table for counties
     * @return void
     */
    private function createCountiesSQLTables():void
    {
        $this->db->exec("DROP TABLE IF EXISTS counties");
        $this->db->exec("CREATE TABLE counties(
                                  id INTEGER PRIMARY KEY, 
                                  name VARCHAR(32),
                                  state_id INT,
                                  tax_rate FLOAT,
                                  tax_amount FLOAT
                                )");

    }

    private function insertStates(array $data)
    {
        $insert = "INSERT INTO states (name) 
                VALUES (:name)";
        $stmt = $this->db->prepare($insert);
        foreach ($data as $key => $value) {
            $stmt->bindParam(':name', $value['name']);
            $stmt->execute();
            $id = $this->db->lastInsertId();
            $this->insertCounties($id, $value['counties']);
        }
    }

    private function insertCounties(int $id, array $data):void
    {
        $sql = "INSERT INTO 
                    counties (
                          name,
                          state_id,
                          tax_rate,
                          tax_amount
                        ) 
                    VALUES (
                      :name,
                      :state_id,
                      :tax_rate,
                      :tax_amount
                    )";
        $stmt = $this->db->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindParam(':name', $value['name']);
            $stmt->bindParam(':state_id', $id);
            $stmt->bindParam(':tax_rate', $value['tax_rate']);
            $stmt->bindParam(':tax_amount', $value['tax_amount']);
            $stmt->execute();
        }

    }

}