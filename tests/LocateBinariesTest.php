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
        //echo "found: '" . implode("\n", $binaries) . "'";

        /*
        if ($this->isWin()) {
            // peek into C:\Windows\System32
            ExecWithFallback::exec('DIR C:\Windows\System32', $output, $returnCode);
            echo "DIR: '" . implode("\r\n", $output) . "'";
        }
        */

        echo 'OS: "' . PHP_OS . '"' . "\n\r";

        ExecWithFallback::exec('echo $PATH', $output, $returnCode);
        echo 'PATH: "' . implode("\n\r", $output) . '"';


    }


    public function testLocateBinariesUsingWhereIs()
    {
        if ($this->isWin()) {
            return;
        }

        // whereis uses a hardcoded list of paths to search. which uses your PATH.
        // You can print the list of paths whereis searches by running the following: sysctl user.cs_path
        //
        // https://apple.stackexchange.com/questions/287467/use-whereis-can-not-find-the-file-in-the-mac

        echo "testing whereis...\n";
        ExecWithFallback::exec('whereis which 2>&1', $output, $returnCode);
        echo "returnCode:" . $returnCode;
        echo "output:" . implode("\n", $output) . "\n";

        //ExecWithFallback::exec('sysctl user.cs_path 2>&1', $output2, $returnCode2);
        ExecWithFallback::exec('whereis -b which 2>&1 2>&1', $output2, $returnCode2);
        echo "returnCode:" . $returnCode2;
        echo "output 2:" . implode("\n", $output2) . "\n";

        $whereIsBinaries = LocateBinaries::locateInCommonSystemPaths('whereis');
        if (count($whereIsBinaries) > 0) {
            $binaries = MethodInvoker::invoke(new LocateBinaries, 'locateBinariesUsingWhereIs', ['ls']);
            //$this->assertGreaterThanOrEqual(1, count($binaries));
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
