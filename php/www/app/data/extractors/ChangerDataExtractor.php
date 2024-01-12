<?php

namespace app\data\extractors;

use epf\epfString;
use stdClass;

class ChangerDataExtractor extends AbstractDataExtractor
{
    protected const TYPE = 'Change';
    private $extractorName = 'changes';

    protected function createAttributesComponents(): void
    {
        $extractorName = $this->extractorName();
        $arrayChange   = [];

        foreach($this->data->$extractorName->attributes as  $attribute) {
            $change             = new stdClass();
            $epfStringChange    = new epfString($attribute);
            $epfStringChanges   = $epfStringChange->split('-');
            $epfStringComponent = new epfString($epfStringChanges[0]->__toString());
            $componentsArray    = $epfStringComponent->split(',');
            $change->components = $this->extractComponents($componentsArray);
            $filesArray         = $epfStringChanges[1]->split(',');
            $change->files      = $this->extractFiles($filesArray);

            $arrayChange[]      = $change;

        }

        $this->data->changes = $arrayChange;
    }

    public function extractorName(): string
    {
        return $this->extractorName;
    }
    public function extractData(): stdClass
    {
        return $this->data;
    }

    private function extractComponents(array $componentsArray): stdClass
    {
        $changeComponents = new stdClass();
        foreach ($componentsArray as $component) {
            $changeComponents->component[] = $component->__toString();
        }

        return $changeComponents;
    }

    private function extractFiles(array $filesArray): stdClass
    {
        $headersExtension         = ['h'];
        $implementationsExtension = ['c', 'cpp'];
        $files                    = new stdClass();

        foreach ($filesArray as $file ) {

            $fileArrayToSplit      = new epfString($file->__toString());
            $fileArray             = $fileArrayToSplit->split(':');
            $fileNameWithExtension = $fileArray[0]->__toString();
            $changedLines          = $fileArray[1]->__toString();

            $epfStringFileNameWithExtension = new epfString($fileNameWithExtension);
            $arrayFileNameWithExtension     = $epfStringFileNameWithExtension->split('.');

            $extension = $arrayFileNameWithExtension[1]->__toString();


            if (in_array($extension, $headersExtension, true)) {
                $files->headers['file'][] = [
                    'name' => $fileNameWithExtension,
                    'changedLines' => $changedLines
                ];
            }

            if (in_array($extension, $implementationsExtension, true)) {
                $files->implementations['file'][] = [
                    'name' =>$fileNameWithExtension,
                    'changedLines' =>  $changedLines
                ];

            }

        }

        return $files;
    }
}