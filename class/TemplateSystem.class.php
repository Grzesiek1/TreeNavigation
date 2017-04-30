<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-28
 * Time: 15:08
 */
declare(strict_types=1);

class TemplateSystem
{
    public $path = 'templates';
    private $variables = array();

    public function display(string $file)
    {
        // define real src element
        $full_path_file = $this->path . '/' . $file;

        ob_start();
        require($full_path_file);
        $return = ob_get_clean();

        // replace variables - ( TemplateSystem::assign() )
        foreach ($this->variables as $key => $value) {
            $return = str_replace('{$' . $key . '}', $value, $return);
        }

        echo $return;
    }

    public function assign(string $var_name, $var_value)
    {
        $this->variables[$var_name] = $var_value;
    }
}