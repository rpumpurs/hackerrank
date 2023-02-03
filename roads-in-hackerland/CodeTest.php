<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CodeTest extends TestCase
{
    private function readFile(string $filePath): string
    {
        $file = fopen($filePath, "r") or die("Unable to open file!");
        $contents = fread($file,filesize($filePath));
        fclose($file);

        return $contents;
    }

    public function provideRoadsInHackerLandData(): array
    {
        $inputMatches = glob('test*.txt');
        $outputMatches = glob('answer*.txt');

        $result = [];

        for ( $i = 0; $i < count($inputMatches); $i++) {
            $result[] = [
                $inputMatches[$i],
                $outputMatches[$i]
            ];
        }

        return $result;
    }

    public function testRoadsInHackerLand(): void
    {

        $tempOutputFile = tmpfile();
        $tempOutputFilePath = stream_get_meta_data($tempOutputFile)['uri'];

        $inputMatches = glob('test*.txt');
        $outputMatches = glob('answer*.txt');

        for ( $i = 0; $i < count($inputMatches); $i++) {
            $inputFileName = $inputMatches[$i];
            $expectedOutputFile = $outputMatches[$i];

            $expectedOutput = $this->readFile($expectedOutputFile);

            exec('export OUTPUT_PATH=' . $tempOutputFilePath . ' && cat ' . $inputFileName .  ' | php code.php');

            $this->assertEquals($expectedOutput, file_get_contents($tempOutputFilePath));
        }
    }
}