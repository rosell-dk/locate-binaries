<?php

namespace LocateBinary\Tests;

use PHPUnit\Framework\TestCase;
use LocateBinary\LocateBinary;

class LocateBinaryTest extends TestCase
{
    public function testLocateInCommonSystemPaths()
    {
        $binaries = LocateBinary::locateInCommonSystemPaths('ls');
        $this->assertGreaterThanOrEqual(1, count($binaries));
    }

    public function locateBinariesUsingWhereIs()
    {
        $whereIsBinaries = LocateBinary::locateInCommonSystemPaths('whereis');
        if (count($whereIsBinaries) > 0) {
            $binaries = LocateBinary::locateBinariesUsingWhereIs('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function locateBinariesUsingWhich()
    {
        $whichBinaries = LocateBinary::locateInCommonSystemPaths('which');
        if (count($whichBinaries) > 0) {
            $binaries = LocateBinary::locateBinariesUsingWhich('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }
}
