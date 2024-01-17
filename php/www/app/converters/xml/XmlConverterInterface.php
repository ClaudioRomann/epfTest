<?php

namespace app\converters\xml;

use stdClass;

interface XmlConverterInterface
{
    public function createHeader(stdClass $header): XmlConverterInterface;

    public function createChangers(stdClass $changes): XmlConverterInterface;

    public function createDescriptions(stdClass $descriptions): XmlConverterInterface;
}