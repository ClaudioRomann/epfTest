<?php

namespace app\data\extractors;

use epf\epfString;
use stdClass;

abstract class AbstractDataExtractorInterface extends AbstractDataExtractor
{
    /**
     * @param $inputTxt string plain text
     */
    public function __construct(string $inputTxt)
    {
        $this->inputTxt = $inputTxt;
    }

    abstract protected function createAttributesComponents(): void;
}