<?php

namespace app\converters\xml;

use epf\epfXmlWriter;

class XmlConverterBuilder implements XmlConverterInterface
{
    private epfXmlWriter $xml;

    public function __construct(string $xmlName)
    {
        $this->xml = new epfXmlWriter($xmlName);
    }


    public function createHeader(array $header): XmlConverterInterface
    {
        if (isset($header['timestamp'])) {
            $this->xml->addAttribute('timestamp', $header['timestamp']);
            unset($header['timestamp']);
        }

        $this->xml->openTag('meta');

        foreach($header as $key=>$value){
            $this->xml->addAttribute($key, $value);
        }

        $this->xml->closeTag();


        return $this;
    }

    public function createChangers(array $changes): XmlConverterInterface
    {
        foreach ($changes as $change) {
            $this->xml->openTag('change');
                $this->xml->openTag('components');
                    foreach ($change['components'] as $component) {
                        $this->xml->addSimpleContentTag('component', $component);
                    }

                $this->xml->closeTag();

                $this->xml->openTag('files');


                    if (isset($change['files']['headers'])) {
                            $this->xml->openTag('headers');
                            foreach ($change['files']['headers'] as $header) {
                                foreach ($header as $key => $value){
                                    $this->xml->openTag('file');
                                        $this->xml->addAttribute('name', $key);
                                        $this->xml->addAttribute('changedLines', $value);
                                    $this->xml->closeTag();
                                }
                            }

                        $this->xml->closeTag();
                    }

                    if (isset($change['files']['implementations'])) {
                        $this->xml->openTag('implementations');
                        foreach ($change['files']['implementations'] as $implementation) {
                            foreach ($implementation as $key => $value){
                                $this->xml->openTag('file');
                                $this->xml->addAttribute('name', $key);
                                $this->xml->addAttribute('changedLines', $value);
                                $this->xml->closeTag();
                            }
                        }

                        $this->xml->closeTag();
                    }


                $this->xml->closeTag();
                $this->xml->closeTag();
        }

        return $this;
    }

    public function createDescriptions(array $descriptions): XmlConverterInterface
    {
        $this->xml->openTag('description');
        $this->xml->addContent($descriptions[0]);
        $this->xml->closeTag();
        return $this;

    }

    public function xmlDocument(): string
    {
        // close first tag
        $this->xml->closeTag();
        return $this->xml->__toString();
    }
}