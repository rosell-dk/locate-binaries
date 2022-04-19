<?php

namespace LocateBinaries\Tests;

use PHPUnit\Framework\TestCase;
use LocateBinaries\LocateBinaries;
use ExecWithFallback\ExecWithFallback;

class LocateBinariesTest extends TestCase
{

    private function isWin()
    {
        return (stripos(PHP_OS, 'WIN') === 0);
    }

    public function testLocateInCommonSystemPaths()
    {
        $binary = ($this->isWin() ? 'where.exe' : 'ls');
        $binaries = LocateBinaries::locateInCommonSystemPaths($binary);
        $this->assertGreaterThanOrEqual(1, count($binaries));
        echo "found: '" . implode("\n", $binaries) . "'";

        if ($this->isWin()) {
            // peek into C:\Windows\System32
            ExecWithFallback::exec('DIR C:\Windows\System32', $output, $returnCode);

            echo "DIR: '" . implode("\r\n", $output) . "'";

        }
    }


    public function testLocateBinariesUsingWhereIs()
    {
        if ($this->isWin()) {
            return;
        }

        $whereIsBinaries = LocateBinaries::locateInCommonSystemPaths('whereis');
        if (count($whereIsBinaries) > 0) {
            $binaries = MethodInvoker::invoke(new LocateBinaries, 'locateBinariesUsingWhereIs', ['ls']);
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function testLocateBinariesUsingWhich()
    {
        if ($this->isWin()) {
            return;
        }
        $whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        if (count($whichBinaries) > 0) {
            $binaries = MethodInvoker::invoke(new LocateBinaries, 'locateBinariesUsingWhich', ['ls']);
            $this->assertGreaterThanOrEqual(1, count($binaries));
        }
    }

    public function testLocateBinariesUsingWhere()
    {
        if (!$this->isWin()) {
            return;
        }
        $binaries = MethodInvoker::invoke(new LocateBinaries, 'locateBinariesUsingWhere', ['where']);
        $this->assertGreaterThanOrEqual(1, count($binaries));
    }


    public function testLocateInstalledBinaries()
    {
        //$whichBinaries = LocateBinaries::locateInCommonSystemPaths('which');
        //if (count($whichBinaries) > 0) {
        $binary = ($this->isWin() ? 'where.exe' : 'ls');
        $binaries = LocateBinaries::locateInstalledBinaries($binary);
        $this->assertGreaterThanOrEqual(1, count($binaries));
        //}
        //echo "found:\n" . implode("\n", $binaries);
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
