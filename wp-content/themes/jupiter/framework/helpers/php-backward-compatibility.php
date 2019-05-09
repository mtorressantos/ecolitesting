<?php
/**
 * This file is responsible to handle PHP backward compatibility
 *
 * @author       Reza Marandi <ross@artbees.net>
 * @copyright    Artbees LTD (c)
 * @link         http://artbees.net
 */

if (!function_exists('array_column'))
{
    function array_column(array $input, $columnKey, $indexKey = null)
    {
        $array = array();
        foreach ($input as $value)
        {
            if (!array_key_exists($columnKey, $value))
            {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey))
            {
                $array[] = $value[$columnKey];
            }
            else
            {
                if (!array_key_exists($indexKey, $value))
                {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
                }
                if (!is_scalar($value[$indexKey]))
                {
                    trigger_error("Key \"$indexKey\" does not contain scalar value");
                    return false;
                }
                $array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}