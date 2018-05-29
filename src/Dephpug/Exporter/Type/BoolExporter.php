<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

/**
 * Exporter for boolean type
 */
class BoolExporter implements iExporter
{
    /**
     * Get type of instance
     *
     * @return string
     */
    public static function getType()
    {
        return 'bool';
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
        return (1 == $xml->property)
                  ? 'true'
                  : 'false';
    }
}
