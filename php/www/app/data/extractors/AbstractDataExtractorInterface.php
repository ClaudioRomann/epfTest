<?php

namespace app\data\extractors;

use epf\epfString;
use stdClass;

abstract class AbstractDataExtractorInterface extends AbstractDataExtractor
{
    protected $inputTxt;
    protected $data;

    /**
     * @param $inputTxt string plain text
     */
    public function __construct(string $inputTxt)
    {
        $this->inputTxt = $inputTxt;
    }
  
    abstract protected function createAttributesComponents(): void;
}