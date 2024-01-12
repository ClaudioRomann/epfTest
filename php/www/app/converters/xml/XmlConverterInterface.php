<?php

namespace app\converters\xml;

interface XmlConverterInterface
{
    public function createHeader(array $header): XmlConverterInterface;

    public function createChangers(array $changes): XmlConverterInterface;

    public function createDescriptions(array $descriptions): XmlConverterInterface;
}