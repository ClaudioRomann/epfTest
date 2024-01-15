<?php

namespace app\data\extractors;

use epf\epfString;

class ChangesDataExtractor extends AbstractDataExtractor
{
    protected const CHANGE_TYPE = 'Change';

    protected const NODE_TYPES = [self::CHANGE_TYPE => 'changes'];

    private $extractorName = 'changes';

    protected function createAttributesComponents(): void
    {
        $nodeType                 = self::NODE_TYPES[self::CHANGE_TYPE];
        $changes                  = $this->data[$nodeType];
        $array                    = [];
        $headersExtension         = ['h'];
        $implementationsExtension = ['c', 'cpp'];
        $arrayIndex               = 0;


        foreach($changes as $change) {

            $epfStringChange     = new epfString($change);
            $pairs               = $epfStringChange->split('-');
            $epfStringComponents = new epfString($pairs[0]->__toString());
            $components          = $epfStringComponents->split(',');
            $epfStringFiles      = new epfString($pairs[1]->__toString());
            $files               = $epfStringFiles->split(',');

            foreach($components as $component) {

                $array[$arrayIndex]['components'][] = $component->__toString();
            }

            foreach($files as $file) {

                // ⚠️⚠️
                // if ($file->contains('.h'))) dont work because it could contain '.hxy' and it will match too
                // in epfString::contains, I can't add a some regex

                $fileNameValue = new epfString($file->__toString());

                // ⚠️ $fileNameValue->split(':'); at this point it doesn't work anymore
                [$fileNameWithExtension, $changedLines] = explode(':', $fileNameValue->__toString());

                $extension = explode('.', $fileNameWithExtension)[1];

                if (in_array($extension, $headersExtension, true)) {
                    $array[$arrayIndex]['files']['headers'][] = [$fileNameWithExtension => $changedLines];
                }

                if (in_array($extension, $implementationsExtension, true)) {
                    $array[$arrayIndex]['files']['implementations'][] = [$fileNameWithExtension => $changedLines];
                }
            }
            $arrayIndex++;
        }

        $this->data[$nodeType] = $array;
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