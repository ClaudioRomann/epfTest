<?php

namespace tests;

use app\Converter;
use PHPUnit\Framework\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @dataProvider provideDataConverter
     */
    public function testConvertInputTxtToOutputXml($inputTxt, $expectedOutputXml)
    {
        $textConvertedToXml = (new Converter($inputTxt))->toXml();

        $expectedXml = simplexml_load_string( $textConvertedToXml);

        $this->assertXmlStringEqualsXmlString($expectedOutputXml, $expectedXml->asXML());
    }

    public function provideDataConverter()
    {
        $textToTest = $this->textToTest();
        $xmlToTest = $this->xmlToTest();

        yield 'convertWithApprovedYes' => [$textToTest[0], $xmlToTest[0]];
        yield 'convertWithApprovedNo' => [$textToTest[1], $xmlToTest[1]];
    }


    private function textToTest(): array
    {
        return [
            'Header|Author=Bill Gates;Timestamp=853082238;Signed=Yes;AutoApprove=12 hours;
Change|Core,Drivers-core.cpp:10,coreutil.c:17,core.h:1,coreutil.h:2
Change|CoreUI-button.cpp:20,button.h:2
Added new button search drivers on microsoft servers.
This will bring us into the Internet age!',
            'Header|Author=Steve Ballmer;Timestamp=853192238;AutoApprove=25 years;
Change|Core-coreutil.c:2
Disable internet driver downloads for now: The internet will not be relevant that soon!'
        ];
    }

    private function xmlToTest(): array
    {
        return [
            '<?xml version="1.0" encoding="UTF-8"?>
<release-info timestamp="1997-01-12 15:17:18">
<meta author="Bill Gates" signed="yes" approved="yes"/>
<change>
<components>
<component>Core</component>
<component>Drivers</component>
</components>
<files>
<headers>
<file name="core.h" changedLines="1"/>
<file name="coreutil.h" changedLines="2"/>
</headers>
<implementations>
<file name="core.cpp" changedLines="10"/>
<file name="coreutil.c" changedLines="17"/>
</implementations>
</files>
</change>
<change>
<components>
<component>CoreUI</component>
</components>
<files>
<headers>
<file name="button.h" changedLines="2"/>
</headers>
<implementations>
<file name="button.cpp" changedLines="20"/>
</implementations>
</files>
</change>
<description>Added new button search drivers on microsoft servers.
This will bring us into the Internet age!
</description>
</release-info>',
            '<?xml version="1.0" encoding="UTF-8"?>
<release-info timestamp="1997-01-13 21:50:38">
<meta author="Steve Ballmer" signed="no" approved="no"/>
<change>
<components>
<component>Core</component>
</components>
<files>
<implementations>
<file name="coreutil.c" changedLines="2"/>
</implementations>
</files>
</change>
<description>Disable internet driver downloads for now: The internet will not be relevant that soon!
</description>
</release-info>'
        ];

    }
}
