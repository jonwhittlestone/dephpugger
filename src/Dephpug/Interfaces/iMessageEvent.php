<?php

namespace Dephpug\Interfaces;

/**
 * Interface to all message parses
 */
interface iMessageEvent
{
    /**
     * Method with the rule to match the xml
     *
     * @param string $xml Xml from DBGP
     *
     * @return bool
     */
    public function match(string $xml);

    /**
     * Method of execution
     *
     * @return any
     */
    public function exec();
}
