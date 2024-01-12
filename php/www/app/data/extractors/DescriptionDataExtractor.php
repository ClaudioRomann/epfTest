<?php

namespace app\data\extractors;
use epf\epfString;

class DescriptionDataExtractor extends AbstractDataExtractor
{

    private const DESCRIPTION_TYPE = 'description';

    private const HEADER_TYPE = 'Header';
    private const CHANGE_TYPE = 'Change';
    private const NODE_TYPES = [
        self::HEADER_TYPE => 'header',
        self::CHANGE_TYPE => 'changes'
    ];

    private $extractorName = 'description';

    public function init(): void
    {
        $this->createNodeComponents();

    }

    protected function createNodeComponents(): void
    {
        $epfStringInputTxt = new epfString($this->inputTxt);
        $array = $epfStringInputTxt->split("\n");
        $epfStringDescription = new epfString();

        foreach ($array as $element) {
            $epfStringElement = new epfString($element);
            $arrayElement = $epfStringElement->split('|');

            if (!self::NODE_TYPES[$arrayElement[0]->__toString()]) {
                $epfStringDescription->append($arrayElement[0]->__toString());
                $epfStringDescription->append("\n");

            }
        }

        $this->data[self::DESCRIPTION_TYPE][] = $epfStringDescription->__toString();
    }

    public function extractorName(): string
    {
        return $this->extractorName;
    }

    public function extractData(): array
    {
        return $this->data[$this->extractorName];
    }
}