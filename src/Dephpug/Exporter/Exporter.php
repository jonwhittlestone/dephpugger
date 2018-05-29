<?php

namespace Dephpug\Exporter;

class Exporter
{
    private $_xml;

    public function setXml($xml)
    {
        $this->_xml = @simplexml_load_string($xml);
    }

    public function printByXml()
    {
        if (!$this->isContentToPrint()) {
            return null;
        }

        $klassName = $this->_getClassExporter();
        $klass = new $klassName();

        return $this->printByClass($klass);
    }

    public function printByClass(iExporter $klass)
    {
        $content = $klass->getExportedVar($this->_xml);

        return " => {$content}\n\n";
    }

    public function isContentToPrint()
    {
        $command = (string) $this->_xml['command'];

        return 'eval' === $command || 'property_get' === $command;
    }

    private function _getClassExporter()
    {
        // Getting value
        $typeVar = (string) $this->_xml->property['type'];

        switch ($typeVar) {
        case 'int':
            return Type\IntegerExporter::class;
        case 'float':
            return Type\FloatExporter::class;
        case 'null':
            return Type\NullExporter::class;
        case 'bool':
            return Type\BoolExporter::class;
        case 'string':
            return Type\StringExporter::class;
        case 'array':
            return Type\ArrayExporter::class;
        case 'object':
            return Type\ObjectExporter::class;
        case 'resource':
            return Type\ResourceExporter::class;
        default:
            return Type\UnknownExporter::class;
        }
    }
}
