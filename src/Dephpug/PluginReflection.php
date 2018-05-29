<?php

namespace Dephpug;

use ReflectionClass;

/**
 * Class to get a list of classes that match an interface
 */
class PluginReflection
{
    /**
     * Objects with interface setted in contructor
     */
    private $_plugins = [];

    /**
     * Interface to match classes
     */
    public $interfaceReflection;

    /**
     * Object Core
     */
    public $core;

    /**
     * Constructor method
     *
     * @param Core   $core                with pointer
     * @param string $interfaceReflection name of interface to match
     */
    public function __construct(
        &$core,
        $interfaceReflection = 'Dephpug\Interfaces\iCommand'
    ) {
        $this->core = $core;
        $this->interfaceReflection = $interfaceReflection;
        $this->setPlugins();
    }

    /**
     * Get all plugins and set in an attribute
     *
     * @return void
     */
    public function setPlugins()
    {
        foreach (get_declared_classes() as $klass) {
            if ($this->isPlugin($klass)) {
                $this->addPlugin($klass);
            }
        }
    }

    /**
     * Get list of plugins
     *
     * @return array $plugins Indicates all plugins added in this obj
     */
    public function getPlugins()
    {
        return $this->_plugins;
    }

    /**
     * Add plugin to list
     *
     * @param string $klass Indicates the name of class to instantiate
     *
     * @return void
     */
    public function addPlugin($klass)
    {
        $obj = new $klass();
        $obj->setCore($this->core);

        if (!in_array($obj, $this->_plugins)) {
            $this->_plugins[] = $obj;
        }
    }

    /**
     * Check if a class is a plugin matching the interface
     *
     * @param string $klass Classname
     *
     * @return bool Indicates if a class is a plugin
     */
    public function isPlugin($klass)
    {
        $reflectionClass = new ReflectionClass($klass);
        $interfaces = array_keys(
            $reflectionClass->getInterfaces()
        );

        return
            !$reflectionClass->isAbstract() &&
            in_array($this->interfaceReflection, $interfaces);
    }
}
