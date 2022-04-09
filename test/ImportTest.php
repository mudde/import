<?php

use Mudde\Import\Exception\ImportException;
use Mudde\Import\Import;
use PHPUnit\Framework\TestCase;

// $this->assertInstanceOf(Emai::class,Email::fromString('user@example.com'));
// $this->expectException(InvalidArgumentException::class); Email::fromString('invalid');
// $this->assertEquals('user@example.com', Email::fromString('user@example.com'));

final class ImportTest extends TestCase
{

    public function testImport(): void
    {
        $this->expectException(ImportException::class);

        $config = json_decode(file_get_contents(__DIR__.'/import.json'), true);
        $config['halt-on-errors'] = true;
        
        $import = new Import($config);
        $import->init();

        foreach($import as $item) {
            $x = $item->getArrayCopy();
        }

        $import->stop();
    } 
}
