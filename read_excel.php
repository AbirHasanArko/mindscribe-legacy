<?php
$zip = new ZipArchive;
if ($zip->open('Agile_Scrum_Project_MindScribe (2).xlsx') === TRUE) {
    $sharedStrings = $zip->getFromName('xl/sharedStrings.xml');
    if ($sharedStrings) {
        $xml = simplexml_load_string($sharedStrings);
        foreach ($xml->si as $si) {
            echo strip_tags($si->asXML()) . "\n";
        }
    } else {
        echo "Could not read xl/sharedStrings.xml\n";
    }
    $zip->close();
} else {
    echo "Could not open excel file.\n";
}
