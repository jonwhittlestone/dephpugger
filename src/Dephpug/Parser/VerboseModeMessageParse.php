<?php

namespace Dephpug\Parser;

use Dephpug\MessageEvent as MessageParse;
use Dephpug\MessageParse as MessageParser;
use Dephpug\Output;

/**
 * Print all XML returned from DBGP protocol if the 
 * config *verboseMode* is actived.
 */
class VerboseModeMessageParse extends MessageParse
{
    /**
     * Ignore the xml and check if the config verboseMode is active
     *
     * @param string $xml Xml received from dbgp protocol
     *
     * @return bool
     */
    public function match(string $xml)
    {
        if ($this->core->config->debugger['verboseMode']) {
            $messageParser = new MessageParser();
            $xml = $messageParser->xmlBeautifier($xml);
            try {
                if ('' !== $xml) {
                    Output::print("\n<comment>{$xml}</comment>\n");
                }
            } catch (\Exception $e) {
                echo $xml;
            }

            return true;
        }

        return false;
    }

    /**
     * Empty method to avoid error in super
     *
     * @return void
     */
    public function exec()
    {
    }
}
