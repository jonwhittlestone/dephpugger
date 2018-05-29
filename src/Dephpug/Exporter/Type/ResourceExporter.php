<?php

namespace Dephpug\Exporter\Type;

use Dephpug\Exporter\iExporter;

/**
 * Exporter for resource type
 */
class ResourceExporter implements iExporter
{
    /**
     * Get type of instance
     *
     * @return string
     */
    public static function getType()
    {
        return 'resource';
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
        return (string) '['.$xml->property.']';
    }
}
