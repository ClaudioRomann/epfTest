<?php

namespace app\data\extractors;

class DataExtractorManager
{

    private $dataExtractorClasses = [];
    private $data;

    public function loadDataExtractors(AbstractDataExtractor $dataExtractor): DataExtractorManager
    {
        $this->dataExtractorClasses [] = $dataExtractor;
        return $this;

    }

    public function retrieveData(): array
    {
        foreach ($this->dataExtractorClasses as $extractors) {
            $extractors->init();
            $this->data[$extractors->extractorName()] = $extractors->extractData();
        }

        return $this->data;
    }
}