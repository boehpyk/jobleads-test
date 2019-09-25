<?php
/**
 * Created by PhpStorm.
 * User: programmer
 * Date: 23/09/2019
 * Time: 14:25
 */

namespace App\Models;

use Slim\App;

class TaxesSQLModel implements TaxesModelInterface
{
    /**
     * SQLite database connection
     * @var $db
     */
    private $db;

    public function __construct(App $app)
    {
        $this->db = $app->getContainer()['db'];
    }

    public function getOverallAmountOfTaxes():array
    {
        $sql = '
            SELECT s.id, s.name, SUM(c.tax_amount) as amount
            FROM states s INNER JOIN counties c
            ON s.id = c.state_id
            GROUP BY s.id, s.name        
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAverageAmountOfTaxes():array
    {
        $sql = '
            SELECT s.id, s.name, AVG(c.tax_amount) as amount
            FROM states s INNER JOIN counties c
            ON s.id = c.state_id
            GROUP BY s.id, s.name        
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAverageCountyTaxRate():array
    {
        $sql = '
            SELECT s.id, s.name, AVG(c.tax_rate) as tax_rate
            FROM states s INNER JOIN counties c
            ON s.id = c.state_id
            GROUP BY s.id, s.name        
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAverageCountryTaxRate():array
    {
        $sql = '
            SELECT AVG(c.tax_rate) as tax_rate
            FROM counties c
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    public function getOverallCountryTaxes():array
    {
        $sql = '
            SELECT SUM(c.tax_amount) as amount
            FROM counties c
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
    }


}