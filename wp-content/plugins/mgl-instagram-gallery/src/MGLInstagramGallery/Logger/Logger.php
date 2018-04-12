<?php

/**
 * Class MGLInstagramGallery_Logger_Logger
 *
 * Writes on a log file message about the behaviour of the system
 */
class MGLInstagramGallery_Logger_Logger
{
    const ERROR = 'ERROR';

    const WARNING = 'WARNING';

    const SUCCESS = 'SUCCESS';

    /**
     * Contains the path where the log will be stored
     * @var $log_file_path string Log file path
     */
    private $log_file_path;

    /**
     * @var $logger_active bool Indicate whether Logger has to add new messages or not
     */
    private $logger_active;

    /**
     * MGLInstagramGallery_Logger_Logger constructor.
     * @param $log_file_path string Pathfile
     */
    public function __construct($log_file_path, $logger_active)
    {
        $this->log_file_path = $log_file_path;

        $this->logger_active = $logger_active;
    }

    /**
     * Add a new message to the log file
     * @param $msg string Message to add
     * @param $type string Type of message
     */
    private function log($msg, $type)
    {
        // If logger is inactive does not add messages to log 
        if (!$this->logger_active) return false;
        
        // Concatenate the type of message with the message
        $text = "$type: $msg\n";

        // Add the message to the log file
        file_put_contents($this->log_file_path, $text, FILE_APPEND);
    }

    /**
     * Shortcut to log. Add to log a error message
     * @param $msg string Message to add
     */
    public function error($msg)
    {
        $this->log($msg, self::ERROR);
    }

    /**
     * Shortcut to log. Add to log a warning message
     * @param $msg string Message to add
     */
    public function warning($msg)
    {
        $this->log($msg, self::WARNING);
    }

    /**
     * Shortcut to log. Add to log a success message
     * @param $msg string Message to add
     */
    public function success($msg)
    {
        $this->log($msg, self::SUCCESS);
    }
}