<?php
/**
 *
 */
class Mk_Validator
{
    private $value;
    public function setValue($value = '')
    {
        $this->value = $value;
        return $this;
    }
    public function getValue()
    {
        return $this->value;
    }

    private $field_name;
    public function setFieldName($field_name = '')
    {
        $this->field_name = $field_name;
        return $this;
    }
    public function getFieldName()
    {
        return $this->field_name;
    }

    private $message;
    public function setMessage($message, $append = true, $param = '')
    {
        $message = str_replace('{param}', $param, str_replace('{field_name}', $this->getFieldName(), __($message, 'mk_framework')));
        if ($append == true)
        {
            $this->message .= $message;
        }
        else
        {
            $this->message = $message;
        }
        return $this;
    }
    public function getMessage()
    {
        return $this->message;
    }
    public function clearMessage()
    {
        $this->message = '';
        return $this;
    }
    public function checkErrorExistence()
    {
        if ($this->message == '')
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function run($config)
    {
        $this->clearMessage();

        $field_name = $this->getFieldName();
        if (empty($field_name) == true || $field_name == '')
        {
            $this->setMessage('You must pass a field name for executing validation', false);
            return false;
        }

        preg_match_all('/(.*?):\s?(.*?)(,|$)/', $config, $validators);
        $validators = array_combine(array_map('trim', $validators[1]), $validators[2]);

        if (is_array($validators) === false || count($validators) == 0)
        {
            $this->setMessage('You must pass an string of validations');
            return false;
        }

        if (array_key_exists('required', $validators))
        {
            $this->requiredCheck();
        }

        if (array_key_exists('string', $validators))
        {
            $this->stringCheck();
        }

        if (array_key_exists('int', $validators))
        {
            $this->intCheck();
        }

        if (array_key_exists('min_len', $validators))
        {
            $this->minLenCheck($validators['min_len']);
        }

        if (array_key_exists('max_len', $validators))
        {
            $this->maxLenCheck($validators['max_len']);
        }

        if (array_key_exists('exact_len', $validators))
        {
            $this->exactLenCheck($validators['exact_len']);
        }

        if (array_key_exists('array', $validators))
        {
            $this->arrayCheck();
        }
        
        if ($this->checkErrorExistence())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function requiredCheck()
    {
        $value = $this->getValue();
        if (empty($value) == true || $value == '')
        {
            $this->setMessage('The {field_name} field is required.');
            return false;
        }
        return true;
    }

    private function arrayCheck()
    {
        $value = $this->getValue();
        if (is_array($value) === false || count($value) < 1)
        {
            $this->setMessage('The {field_name} field must be an array with at least one element.');
            return false;
        }
        return true;
    }

    private function stringCheck()
    {
        $value = $this->getValue();
        if (is_string($value) === false)
        {
            $this->setMessage('The {field_name} field must have an string value.');
            return false;
        }
        return true;
    }

    private function intCheck()
    {
        $value = $this->getValue();
        if (is_int($value) == false)
        {
            $this->setMessage('The {field_name} field must contain an integer.');
            return false;
        }
        return true;
    }

    private function minLenCheck($min_len)
    {
        $value = $this->getValue();
        if (strlen($value) < $min_len)
        {
            $this->setMessage('The {field_name} field must be at least {param} characters in length.', true, $min_len);
            return false;
        }
        return true;
    }
    private function maxLenCheck($max_len)
    {
        $value = $this->getValue();
        if (strlen($value) > $max_len)
        {
            $this->setMessage('The {field_name} field cannot exceed {param} characters in length.', true, $max_len);
            return false;
        }
        return true;
    }

    private function exactLenCheck($exact_len)
    {
        $value = $this->getValue();
        if (strlen($value) != $exact_len)
        {
            $this->setMessage('The {field_name} field must be exactly {param} characters in length.', true, $exact_len);
            return false;
        }
        return true;
    }
}