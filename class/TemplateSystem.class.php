<?php

/**
 * Created by PhpStorm.
 * User: Grzegorz Chwiluk
 * Date: 2017-04-28
 * Time: 15:08
 */
class TemplateSystem
{
    public $path = 'templates';
    public $variables = array();

    function display($file)
    {
        // define real src element
        $full_path_file = $this->path . '/' . $file;

        ob_start();
        require($full_path_file);
        $return = ob_get_clean();

        // replace variables - ( TemplateSystem::assing() )
        foreach ($this->variables as $key => $value) {
            $return = str_replace('{$' . $key . '}', $value, $return);
        }

        echo $return;
    }

    function assign($var_name, $var_value)
    {
        $this->variables[$var_name] = $var_value;
    }
}