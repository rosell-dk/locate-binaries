<?php

namespace LocateBinaries\Tests;

use PHPUnit\Framework\TestCase;
use LocateBinaries\LocateBinaries;

class LocateBinariesTest extends TestCase
{
    public function testLocateInCommonSystemPaths()
    {
        $binaries = LocateBinaries::locateInCommonSystemPaths('ls');
        $this->assertGreaterThanOrEqual(1, count($binaries));
    }

    public function locateBinariesUsingWhereIs()
    {
        $whereIsBinaries = LocateBinaries::locateInCommonSystemPaths('whereis');
        if (count($whereIsBinaries) > 0) {
            $binaries = LocateBinaries::locateBinariesUsingWhereIs('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function locateBinariesUsingWhich()
    {
        $whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        if (count($whichBinaries) > 0) {
            $binaries = LocateBinaries::locateBinariesUsingWhich('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }
}
