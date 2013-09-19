<?php

namespace Log;

class MyLogWriter
{

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     */
    public function __construct($settings = array())
    {
        //Merge user settings
        $this->settings = array_merge(array(
            'path' => '../logs',
            'file_format' => 'Y-m-d',
            'folder_format' => 'Y-m',
            'extension' => 'log',
            'message_format' => '%label% - %date% - %message%'
        ), $settings);

        //Remove trailing slash from log path
        $this->settings['path'] = rtrim($this->settings['path'], DIRECTORY_SEPARATOR);
    }

    /**
     * @param $object
     * @param $level
     * @internal param mixed $message
     */
    public function write($object, $level)
    {
        //Determine label
        $label = 'DEBUG';
        switch ($level) {
            case \Slim\Log::FATAL:
                $label = 'FATAL';
                break;
            case \Slim\Log::ERROR:
                $label = 'ERROR';
                break;
            case \Slim\Log::WARN:
                $label = 'WARN';
                break;
            case \Slim\Log::INFO:
                $label = 'INFO';
                break;
        }

        //Get formatted log message
        $message = str_replace(array(
            '%label%',
            '%date%',
            '%message%'
        ), array(
            $label,
            date('c'),
            (string)$object
        ), $this->settings['message_format']);

        //Open resource handle to log file
        if (!$this->resource) {

            //Create the folder if any
            $foldername = date($this->settings['folder_format']);
            if (!is_dir("../logs/" . $foldername)) {
                mkdir("../logs/" . $foldername, 0777);
            }

            // Then create the file
            $filename = date($this->settings['file_format']) . '.' . $this->settings['extension'];
            $this->resource = fopen($this->settings['path'] . DIRECTORY_SEPARATOR. $foldername . DIRECTORY_SEPARATOR . $filename, 'a');
        }

        //Output to resource
        fwrite($this->resource, $message . PHP_EOL);
    }

}