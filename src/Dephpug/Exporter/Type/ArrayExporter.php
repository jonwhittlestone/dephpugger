<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;
use Dephpug\Dbgp\Client;

/**
 * Exporter for array type
 */
class ArrayExporter implements iExporter
{
    /**
     * Get type of instance
     *
     * @return string
     */
    public static function getType()
    {
        return 'array';
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
     * Command to get formated array value
     *
     * @param string $command Command to parse
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
