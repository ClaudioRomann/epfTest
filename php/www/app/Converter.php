<?php

namespace app;

use app\converters\xml\XmlConverterBuilder;
use app\data\extractors\ChangerDataExtractor;
use app\data\extractors\DataExtractorManager;
use app\data\extractors\DescriptionDataExtractor;
use app\data\extractors\HeaderDataExtractor;
use epf\epfPerformanceTimer;

class Converter
{
    private $xmlName = 'release-info';

    private $inputTxt;

    private $timer;

    /**
     * @param $inputTxt string plain text
     */
    public function __construct(string $inputTxt)
    {
        $this->timer = new epfPerformanceTimer(true);
        $this->inputTxt = $inputTxt;
    }

    public function toXml():string
    {
        $this->timer->addMeasurePoint('DataExtractors starting');

        $dataExtractorManager = new DataExtractorManager();

        $data = $dataExtractorManager
            ->loadDataExtractors(new HeaderDataExtractor($this->inputTxt))
            ->loadDataExtractors(new ChangerDataExtractor($this->inputTxt))
            ->loadDataExtractors(new DescriptionDataExtractor($this->inputTxt))
            ->retrieveData();

        $this->timer->addMeasurePoint('DataExtractors ending');

        $this->timer->addMeasurePoint('XmlBuilder starting');

        $xmlBuilder = new XmlConverterBuilder($this->xmlName);
        $stringXml = $xmlBuilder->createHeader($data['header'])
            ->createChangers($data['changes'])
            ->createDescriptions($data['description'])
            ->xmlDocument();
        $this->timer->addMeasurePoint('XmlBuilder ending');

        return $stringXml;
    }

    public function timer(): epfPerformanceTimer
    {
        return $this->timer;
    }
}