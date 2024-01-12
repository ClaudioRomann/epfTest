<?php

namespace app\converters\xml;

use epf\epfXmlWriter;
use stdClass;

class XmlConverterBuilder implements XmlConverterInterface
{
    private $finalXml;
    private $xmlName;

    public function __construct(string $xmlName)
    {
        $this->xmlName = $xmlName;
    }

    public function createHeader(stdClass $header): XmlConverterInterface
    {
        $objectHeader = $header->header;
        $this->finalXml = new epfXmlWriter($this->xmlName);

        if ($objectHeader->attributes->timestamp) {
            $this->finalXml->addAttribute('timestamp', $objectHeader->attributes->timestamp);
            unset($objectHeader->attributes->timestamp);
        }

        $xml = $this->createTagWithAttribute('meta', $objectHeader->attributes);

        $this->finalXml->addXml($xml);

        return $this;
    }

    public function createChangers(stdClass $changes): XmlConverterInterface
    {
        $objectChanges = $changes->changes;

        foreach ($objectChanges as $change) {
            $xmlChange = new epfXmlWriter('change');

            $xmlComponents = $this->createComponents($change->components);
            $xmlFiles = $this->createFiles($change->files);

            $xmlChange->addXml($xmlComponents);
            $xmlChange->addXml($xmlFiles);
            $xmlChange->closeTag();

            $this->finalXml->addXml($xmlChange);
        }

        return $this;
    }

    public function createDescriptions(stdClass $descriptions): XmlConverterInterface
    {
        $objectDescription = $descriptions->description;
        $this->finalXml->openTag('description');
        $this->finalXml->addContent($objectDescription);
        $this->finalXml->closeTag();

        return $this;
    }

    public function xmlDocument(): string
    {
        // close first tag
        $this->finalXml->closeTag();
        return $this->finalXml->__toString();
    }

    private function createTagWithAttribute(string $tagName, stdClass $attributes): epfXmlWriter
    {
        $xml = new epfXmlWriter($tagName);

        foreach ($attributes as $attributeName => $value) {
            $xml->addAttribute($attributeName, $value);
        }
        $xml->closeTag();

        return $xml;
    }

    private function createComponents(stdClass $components): epfXmlWriter
    {
        $xmlComponents = new epfXmlWriter('components');
        foreach ($components->component as $component) {
            $xmlComponents->addSimpleContentTag('component', $component);
        }

        $xmlComponents->closeTag();
        return $xmlComponents;

    }

    private function createFiles(stdClass $files):epfXmlWriter
    {
        $xmlFiles = new epfXmlWriter('files');

        if (isset($files->headers['file'])) {
            $xmlFilesHeaders = new epfXmlWriter('headers');
            foreach ($files->headers['file'] ?? '' as $file) {
                $xmlFilesHeadersFile = $this->createTagWithAttribute('file', (object) $file);
                $xmlFilesHeaders->addXml($xmlFilesHeadersFile);
            }
            $xmlFilesHeaders->closeTag();

            $xmlFiles->addXml($xmlFilesHeaders);
        }

        if ($files->implementations['file']) {
            $xmlFilesImplementations = new epfXmlWriter('implementations');

            foreach ($files->implementations['file'] as $file) {
                $xmlFilesHeadersFile = $this->createTagWithAttribute('file', (object) $file);
                $xmlFilesImplementations->addXml($xmlFilesHeadersFile);
            }

            $xmlFilesImplementations->closeTag();

            $xmlFiles->addXml($xmlFilesImplementations);

        }

        $xmlFiles->closeTag();

        return $xmlFiles;
    }

}