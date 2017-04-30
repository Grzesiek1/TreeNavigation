<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-22
 * Time: 15:02
 * Class generate tree folders as multi arrays based on database.
 * Return json data or arrays.
 */
declare(strict_types=1);

class GenerateTreeArrays extends GenerateTreeBased
{

    /*
     * Additional "extends"
     */
    private $ActionFiles;

    function __construct($db)
    {
        parent::__construct($db);
        $this->ActionFiles = new Files\ActionFiles($this->db);
    }

    function __call($method, $args)
    {
        return call_user_func_array(array($this->ActionFiles, $method), $args);
    }

    /* -- */

    private $return_array;

    /*
     * Generate main tree
     */
    public function generate_tree(bool $array = false)
    {
        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree order by parent ASC, display_order");
        $res->execute();

        // tag starting group
        $this->return .= '[';
        while ($row = $res->fetch()) {
            // check whether exist branch in this element
            if ($this->check_have_child((int)$row['id'])) {
                // generate child if exist
                $this->generate_child((int)$row['id'], $row['name']);
            } else {
                // check whether name already occur
                // (example - When the item has already been exposed elsewhere as a child)
                if (!$this->whether_value_occurred($row['name'])) {
                    // generate main element
                    $this->return .= '{"id":' . $row['id'] . ', "text": "' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '",' . $this->files_generate((int)$row['id']) . '},';
                    $this->return_array['id'][] = $row['id'];
                    $this->return_array['name'][] = $row['name'];
                }
            }
        }
        // finally, close the group
        $this->return .= ']';

        // remove char , before end block
        $this->return = str_replace("},]", "}]", $this->return);

        if ($array == true) {
            return $this->return_array;
        } else {
            return $this->return;
        }
    }

    /*
     * Gets as a parameter id and name main branch
     * Returns elements of a subgroup to global variable $return
     */
    private function generate_child(int $id, string $parent_name)
    {
        $closed_tag = 0;

        // generate main element if not generate early
        if (!$this->whether_value_occurred($parent_name)) {
            // generate main element
            $this->return .= '{"id":' . $id . ', "text": "' . $parent_name . '", ' . $this->files_generate((int)$id);
            $this->return_array['id'][] = $id;
            $this->return_array['name'][] = $parent_name;
        }

        // get all "child" which own to parent which calling function
        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree WHERE parent = :id order by display_order");
        $res->bindValue(':id', $id, PDO::PARAM_INT);
        $res->execute();

        // generate child element
        while ($row = $res->fetch()) {
            // important type var!
            if (!$this->whether_value_occurred((int)$row['parent'])) {
                // tag starting group
                $this->return .= ',"nodes": [';
                $closed_tag = 1;
            }
            // generate all no not occurring elements
            if (!$this->whether_value_occurred($row['name'])) {
                // if element have a second sub branch
                if ($this->check_have_child((int)$row['id'])) {
                    // show element
                    $this->return .= '{"id":' . $row['id'] . ', "text": "' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '",' . $this->files_generate((int)$row['id']);
                    $this->return_array['id'][] = $row['id'];
                    $this->return_array['name'][] = $row['name'];
                    // and use function generate sub branch (itself)
                    $this->generate_child((int)$row['id'], $row['name']);
                    // if element haven't a second sub branch only show element
                } else {
                    // show element
                    $this->return .= '{"id":' . $row['id'] . ', "text": "' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '", ' . $this->files_generate((int)$row['id']) . '},';
                    $this->return_array['id'][] = $row['id'];
                    $this->return_array['name'][] = $row['name'];
                }
            }
        }
        // finally, close the group
        if ($closed_tag == 1) {
            $this->return .= ']},';
        }
    }

}