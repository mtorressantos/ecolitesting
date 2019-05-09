<?php
namespace Devbees\BeesLog;

/**
 * @author  Reza Marandi <ross@artbees.net>
 * @version 1.0.0
 */

class logger
{
    protected $options = array(
        'extension'     => 'txt',
        'dateFormat'    => 'Y-m-d G:i:s',
        'filename'      => false,
        'prefix'        => 'log_',
        'appendEnvInfo' => false,
        'envInfo'       => [],
    );

    protected $logLevels = array(
        'emergency' => 0,
        'alert'     => 1,
        'critical'  => 2,
        'error'     => 3,
        'warning'   => 4,
        'notice'    => 5,
        'info'      => 6,
        'debug'     => 7,
    );

    protected $logLevelThreshold = 'debug';
    protected $logDirectoryPath;

    /* IF system is active or not (for any reason) */
    protected $system_status;

    private $tags;
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }
    public function getTags()
    {
        return $this->tags;
    }

    public function setEnvInfo($envInfo = array())
    {
        $this->options['envInfo'] = $envInfo;
        return $this;
    }
    public function getEnvInfo()
    {
        return $this->options['envInfo'];
    }

    /**
     * Class constructor
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $logLevelThreshold Which error should be logged ?
     * @param array $options array of options , it will be replaced over default configs
     *
     */
    public function __construct($logLevelThreshold = 'debug', array $options = array())
    {
        $this->options['logsDirectoryPath'] = ABSPATH . '/wp-content/uploads/mk_logs';
        $this->options                      = array_merge($this->options, $options);
        $this->logLevelThreshold            = $logLevelThreshold;
        $this->logDirectoryPath             = rtrim($this->options['logsDirectoryPath'], DIRECTORY_SEPARATOR);
        $this->system_status = $this->checkLogDirectory();
    }

    /**
     * This method will create an emergency log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function emergency($subject = '', $message)
    {
        $this->log('emergency', $subject, $message);
    }

    /**
     * This method will create an alert log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function alert($subject = '', $message)
    {
        $this->log('alert', $subject, $message);
    }

    /**
     * This method will create a critical log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function critical($subject = '', $message)
    {
        $this->log('critical', $subject, $message);
    }

    /**
     * This method will create an error log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function error($subject = '', $message)
    {
        $this->log('error', $subject, $message);
    }

    /**
     * This method will create a warning log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function warning($subject = '', $message)
    {
        $this->log('warning', $subject, $message);
    }

    /**
     * This method will create a notice log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function notice($subject = '', $message)
    {
        $this->log('notice', $subject, $message);
    }

    /**
     * This method will create an info log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function info($subject = '', $message)
    {
        $this->log('info', $subject, $message);
    }

    /**
     * This method will create a debug log block
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */
    public function debug($subject = '', $message)
    {
        $this->log('debug', $subject, $message);
    }

    /**
     * This method is responsible to create a proper message content with right format and
     * then hand over it for writing in log file
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $logLevel Log level of input log request.
     * @param string $subject Title of message
     * @param string $message Content of message
     *
     */

    public function log($logLevel, $subject = '', $message)
    {
        if ($this->logLevels[$logLevel] > $this->logLevels[$this->logLevelThreshold] || !$this->system_status)
        {
            return;
        }

        if (is_array($message))
        {
            $message = $this->objectToString($message);
        }

        $this->write($this->formatMessage($subject, $message, $logLevel));
    }

    /**
     * This method is responsible to create a beautify string from an array
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param array $message an array that need to be convert to string
     *
     * @return string will return converted array
     *
     */
    public function objectToString($message)
    {
        $result = PHP_EOL;
        $result .= 'Array Count - ' . count($message) . ' Elements';
        array_walk($message, function (&$value, &$key) use (&$result)
        {
            $result .= PHP_EOL;
            $result .= "\t ($key)  => ";
            if (is_array($value))
            {
                $result .= $this->objectToString($value);
            }
            else
            {
                $result .= "$value (" . gettype($value) . ")";
            }

        });
        return $result;
    }

    /**
     * This method is responsible to format message based on template we have
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $subject Title of message
     * @param string $message Content of message
     * @param string $logLevel Log level of input log request.
     *
     * @return string will return formatted message
     *
     */
    public function formatMessage($subject, $message, $logLevel)
    {
        $template = '============= {logLevel} - {subject} =============' . PHP_EOL . PHP_EOL .
            'Time        : {timestamp}' . PHP_EOL .
            'Log Level   : {logLevel}' . PHP_EOL .
            'Subject     : {subject}' . PHP_EOL .
            'Tags        : {tags}' . PHP_EOL .
            'Env info    : {env}' . PHP_EOL .
            'Message     : {message}' . PHP_EOL . PHP_EOL . PHP_EOL;
        $replace_array = ['{logLevel}', '{subject}', '{message}', '{timestamp}', '{tags}', '{env}'];
        $to_array      = [strtoupper($logLevel), $subject, $message, $this->getTimestamp(), $this->getTags(), $this->getEnviornmentDetail()];
        return str_replace($replace_array, $to_array, $template);
    }

    /**
     * This method is responsible to write
     *
     * @author Reza Marandi <ross@artbees.net>
     *
     * @param string $message Content of log block
     *
     */
    public function write($message)
    {
        file_put_contents($this->logFilePath(), $message, FILE_APPEND | LOCK_EX);
    }
    public function logFilePath()
    {
        $prefix   = $this->options['prefix'];
        $log_name = $prefix . date('Y-m-d') . '.log';
        $log_path = $this->logDirectoryPath . DIRECTORY_SEPARATOR . $log_name;

        if (file_exists($log_path))
        {
            return $log_path;
        }
        if (is_writable($this->logDirectoryPath))
        {
            $response = file_put_contents($log_path, '');
            if ($response === false)
            {
                return false;
            }
            return $log_path;
        }
        return false;

    }

    /*====================== HELPERS ============================*/
    private function getTimestamp()
    {
        // $originalTime = microtime(true);
        // $micro        = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
        // $date         = new DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));
        // return $date->format($this->options['dateFormat']);

        return date($this->options['dateFormat']);
    }
    public function checkLogDirectory()
    {
        if (!file_exists($this->logDirectoryPath))
        {
            if (!@mkdir($this->logDirectoryPath, 0777, true))
            {
                return false;
            }

            return true;
        }
        else
        {
            if (!is_writable($this->logDirectoryPath))
            {
                return false;
            }

            return true;
        }
    }
    public function getEnviornmentDetail()
    {
        $options = $this->getEnvInfo();
        if (!$this->options['appendEnvInfo'] || !is_array($options) || count($options) < 1)
        {
            return;
        }
        $response = PHP_EOL;
        if (in_array('wp_version', $options))
        {
            global $wp_version;
            $response .= 'wp_version => ' . $wp_version . PHP_EOL;
        }
        if (in_array('php_version', $options))
        {
            $response .= 'php_version => ' . phpversion() . PHP_EOL;
        }
        if (in_array('max_execution', $options))
        {
            $response .= 'max_execution => ' . ini_get('max_execution_time') . PHP_EOL;
        }
        return $response;
    }
}
