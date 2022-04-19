<?php

namespace LocateBinaries\Tests;

use PHPUnit\Framework\TestCase;
use LocateBinaries\LocateBinaries;

class LocateBinariesTest extends TestCase
{

    private function isWin()
    {
        return (stripos(PHP_OS, 'WIN') === 0);
    }

    public function testLocateInCommonSystemPaths()
    {
        $binary = ($this->isWin() ? 'DIR' : 'ls');
        $binaries = LocateBinaries::locateInCommonSystemPaths($binary);
        $this->assertGreaterThanOrEqual(1, count($binaries));
    }

/*
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
*/
    public function testLocateInstalledBinaries()
    {
        //$whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        //if (count($whichBinaries) > 0) {
        $binary = ($this->isWin() ? 'where' : 'ls');
            $binaries = LocateBinaries::locateInstalledBinaries($binary);
            $this->assertGreaterThanOrEqual(1, count($binaries));
        //}
        echo "found:\n" . implode("\n", $binaries);
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
