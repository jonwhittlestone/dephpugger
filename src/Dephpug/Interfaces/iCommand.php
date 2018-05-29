<?php

namespace Dephpug\Interfaces;

/**
 * Set methods for all commands
 */
interface iCommand
{
    /**
     * Get the Command name
     *
     * @return string
     */
    public function getName();

    /**
     * Get the alias of the command
     *
     * @return string
     */
    public function getAlias();

    /**
     * Get a one line description of this command
     *
     * @return string
     */
    public function getShortDescription();

    /**
     * Get the full description of the command
     *
     * @return string
     */
    public function getDescription();

    /**
     * The regexp to match the command
     *
     * @return string
     */
    public function getRegexp();
}
