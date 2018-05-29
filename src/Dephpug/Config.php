<?php

namespace Dephpug;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class to get the default config using file *.dephpugger.yml*
 */
class Config
{
    /**
     * Default config that will be replaced by *.dephpugger.yml* file
     */
    private $_defaultConfig = [
        'server' => [
            'port' => 8888,
            'host' => 'localhost',
            'path' => null,
            'file' => '',
        ],
        'debugger' => [
            'port' => 9005,
            'host' => 'localhost',
            'lineOffset' => 6,
            'verboseMode' => false,
            'historyFile' => '.dephpugger_history',
        ],
    ];

    /**
     * Array with configurations
     */
    private $_config;

    /**
     * Get the attribute config
     *
     * @return array $config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Read the file (if exists) .dephpugger.yml
     *
     * @return void
     */
    public function configure()
    {
        $config = $this->getConfigFromFile();
        $this->_config = array_replace_recursive($this->_defaultConfig, $config);
    }

    /**
     * Get path of file *.dephpugger.yml*
     *
     * @return void
     */
    public function getPathFile()
    {
        return getcwd().'/.dephpugger.yml';
    }

    /**
     * Get configuration from file *.dephpugger.yml*[
     *
     * @return array $config
     */
    public function getConfigFromFile()
    {
        $config = [];
        $path = $this->getPathFile();
        if (file_exists($path)) {
            try {
                $config = Yaml::parse(file_get_contents($path));
            } catch (ParseException $e) {
                throw new ParseException('The file .dephpugger.yml is invalid');
            }
        }

        return $config;
    }

    /**
     * Magic Method to get config as an attribute
     *
     * @param string $key Name of attribute
     *
     * @return array|null
     */
    public function __get($key)
    {
        if (isset($this->_config[$key])) {
            return $this->_config[$key];
        }

        return null;
    }

    /**
     * Change the attribute in configuration debugger
     *
     * @param string $key   Indicates the debugger's attribute
     * @param string $value Indicates the new value
     *
     * @return void
     */
    public function setNewDebuggerValue($key, $value)
    {
        if (isset($this->_config['debugger'][$key])) {
            $this->_config['debugger'][$key] = $value;
        }
    }
}
