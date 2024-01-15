<?php

namespace app\data\extractors;

class DataExtractorManager
{

    private array $dataExtractorClasses = [];
    private array $data;

    public function loadDataExtractors(AbstractDataExtractor $dataExtractor): self
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