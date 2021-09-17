<?php

namespace Tests\Unit;

use App\Helpers\DurationOfReading;
use PHPUnit\Framework\TestCase;

class DurationOfReadingPostTest extends TestCase
{

    public function testDurationTimePost()
    {
        $text = 'this is for test';

        $dor = new DurationOfReading($text);

        $this->assertEquals(4, $dor->getTimePerSecond());
        $this->assertEquals(4/60, $dor->getTimePerMinute());
    }

}
