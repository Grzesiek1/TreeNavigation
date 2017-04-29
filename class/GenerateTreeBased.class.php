<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-24
 * Time: 17:37
 * Base class for class GenerateTreeArrays and GenerateTreeHtml
 */
declare(strict_types=1);

class GenerateTreeBased
{
    protected $array, $return;

    function __construct($db)
    {
        $this->db = $db;
        $this->array = array();
    }

    /*
     * Gets as a parameter element id
     * Return true if element contain sub branch
     */
    protected function check_have_child(int $id): bool
    {
        $res = $this->db->prepare("SELECT COUNT(id) FROM tree WHERE parent = :id");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        if ($res->fetchColumn() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Gets as a parameter any value
     * Return true if value occurs during the life of the object this class
     * Method used by GeneratorTree as auxiliary
     */
    protected function whether_value_occurred($value): bool
    {
        $exist_value = 'no';

        for ($x = 0; $x < sizeof($this->array); $x++) {
            if ($value === $this->array[$x]) {
                $exist_value = 'yes';
            }
        }

        array_push($this->array, $value);

        if ($exist_value == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}