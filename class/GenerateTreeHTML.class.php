<?php
/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-24
 * Time: 17:37
 * Class generate tree in form html based on tag <ul> <li>.
 */

declare(strict_types = 1);

class GenerateTreeHtml extends GenerateTreeBased
{
    /*
     * Generate main tree
     */
    function generate_tree()
    {
        $res = $this->db->prepare("SELECT id, name, parent, display_order FROM tree order by parent ASC, display_order");
        $res->execute();

        // tag starting group
        $this->return .= '<ul>';
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
                    $this->return .= '<li>' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '</li>';
                }
            }
        }
        // finally, close the group
        $this->return .= '</ul>';

        return $this->return;
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
            $this->return .= '<li>' . $parent_name;
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
                $this->return .= '<ul>';
                $closed_tag = 1;
            }

            // generate all no not occurring elements
            if (!$this->whether_value_occurred($row['name'])) {
                // if element have a second sub branch
                if ($this->check_have_child((int)$row['id'])) {
                    // show element
                    $this->return .= '<li>' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '</li>';
                    // and use function generate sub branch (itself)
                    $this->generate_child((int)$row['id'], $row['name']);
                    // if element haven't a second sub branch only show element
                } else {
                    // show element
                    $this->return .= '<li>' . filter_var($row['name'], FILTER_SANITIZE_STRING) . '</li>';
                }
            }
        }
        // finally, close the group
        if ($closed_tag == 1) {
            $this->return .= '</ul>';
        }

    }

}