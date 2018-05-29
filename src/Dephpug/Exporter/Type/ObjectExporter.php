<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;
use Dephpug\Dbgp\Client;

/**
 * Exporter for object type
 */
class ObjectExporter implements iExporter
{
    /**
     * Get type of instance
     *
     * @return string
     */
    public static function getType()
    {
        return 'object';
    }

    /**
     * Get exported variable value
     *
     * @param obj $xml Parsed XML
     *
     * @return string
     */
    public function getExportedVar($xml)
    {
        $command = "var_export({$xml->property->attributes()['name']}, true);";
        $responseXDebug = $this->getResponseByCommand($command);
        $newXml = simplexml_load_string($responseXDebug);
        $content = base64_decode((string) $newXml->property);

        return $content;
    }

    /**
     * Get response by a object command
     *
     * @param string $command Command received
     *
     * @return string
     */
    public function getResponseByCommand($command)
    {
        $dbgpClient = new Client();
        $dbgpClient->eval($command);

        return $dbgpClient->getResponse();
    }
}
