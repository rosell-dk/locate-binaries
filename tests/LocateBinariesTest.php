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

    public function testLocateBinariesUsingWhereIs()
    {
        $whereIsBinaries = LocateBinaries::locateInCommonSystemPaths('whereis');
        //$this->assertGreaterThanOrEqual(1, count($whereIsBinaries));
        if (count($whereIsBinaries) > 0) {
            $binaries = LocateBinaries::locateBinariesUsingWhereIs('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function testLocateBinariesUsingWhich()
    {
        $whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        if (count($whichBinaries) > 0) {
            $binaries = LocateBinaries::locateBinariesUsingWhich('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function testLocateInstalledBinaries()
    {
        $whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        if (count($whichBinaries) > 0) {
            $binaries = LocateBinaries::locateInstalledBinaries('ls');
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function testLocateInstalledNoBinariesFound()
    {
        $binaries = LocateBinaries::locateInstalledBinaries('lsbananaflip');
        $this->assertEquals(0, count($binaries));
    }
/*
    public function testLocateInstalledNoExecAvail()
    {
        // If no exec() is avail, we expect Exception... But how do we simulate that exec() is unavailable?
        // $this->expectException(\Exception::class);
        // how to test this?
    }*/


}
