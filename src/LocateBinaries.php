<?php

namespace LocateBinaries;

use FileUtil\FileExists;
use ExecWithFallback\ExecWithFallback;

/**
 * Locate path (or multiple paths) of a binary
 *
 * @package    LocateBinaries
 * @author     Bjørn Rosell <it@rosell.dk>
 */
class LocateBinaries
{

    /**
     * Locate binaries by looking in common system paths.
     *
     * We try a small set of common system paths, such as "/usr/bin".
     * On Windows, we only try C:\Windows\System32
     * Note that you do not have to add ".exe" file extension on Windows, it is taken care of
     *
     * @param  string $binary  the binary to look for (ie "cwebp")
     *
     * @return array binaries found in common system locations
     */
    public static function locateInCommonSystemPaths($binary)
    {
        $binaries = [];

        $commonSystemPaths = [];

        if (stripos(PHP_OS, 'WIN') === 0) {
            $commonSystemPaths = [
                'C:\Windows\System32',
            ];
            $binary .= '.exe';
        } else {
            $commonSystemPaths = [
                '/usr/bin',
                '/usr/local/bin',
                '/usr/gnu/bin',
                '/usr/syno/bin',
                '/bin',
            ];
        }

        foreach ($commonSystemPaths as $dir) {
            // PS: FileExists might throw if exec() or similar is unavailable. We let it.
            // - this class assumes exec is available
            if (FileExists::fileExistsTryHarder($dir . DIRECTORY_SEPARATOR . $binary)) {
                $binaries[] = $dir . DIRECTORY_SEPARATOR . $binary;
            }
        }
        return $binaries;
    }

    /**
     * Locate installed binaries using ie "whereis -b cwebp" (for Linux, Mac, etc)
     *
     * @return array  Array of paths locateed (possibly empty)
     */
    private static function locateBinariesUsingWhereIs($binary)
    {
        $isMac = (PHP_OS != 'Darwin');
        $command = 'whereis ' . ($isMac ? '-b ' : '') . $binary . ' 2>&1';
        echo 'Command: ' . $command . "\n\r";
        ExecWithFallback::exec($command, $output, $returnCode);
        if (($returnCode == 0) && (isset($output[0]))) {
            $result = $output[0];
            if ($isMac) {
                // Hm, actually I don't know how the result looks on Mac when there are several matches
                // the following works when there is one match
                return $output[0];
            } else {
                // Ie: "cwebp: /usr/bin/cwebp /usr/local/bin/cwebp"
                if (preg_match('#^' . $binary . ':\s(.*)$#', $result, $matches)) {
                    return explode(' ', $matches[1]);
                }
            }
        }
        return [];
    }

    /**
     * locate installed binaries using "which -a cwebp"
     *
     * @param  string $binary  the binary to look for (ie "cwebp")
     *
     * @return array  Array of paths locateed (possibly empty)
     */
    private static function locateBinariesUsingWhich($binary)
    {
        // As suggested by @cantoute here:
        // https://wordpress.org/support/topic/sh-1-usr-local-bin-cwebp-not-found/
        ExecWithFallback::exec('which -a ' . $binary . ' 2>&1', $output, $returnCode);
        if ($returnCode == 0) {
            return $output;
        }
        return [];
    }

    /**
     * Locate binaries using where.exe (for Windows)
     *
     * @param  string $binary  the binary to look for (ie "cwebp")
     *
     * @return array binaries found
     */
    private static function locateBinariesUsingWhere($binary)
    {
        ExecWithFallback::exec('where.exe ' . $binary . ' 2>&1', $output, $returnCode);
        if ($returnCode == 0) {
            return $output;
        }
        return [];
    }

    /**
     * Locate installed binaries
     *
     * For linuk, we use "which -a" or, if that fails "whereis -b"
     * For Windows, we use "where.exe"
     * These commands only searces within $PATH. So it only finds installed binaries (which is good,
     * as it would be unsafe to deal with binaries found scattered around)
     *
     * @param  string $binary  the binary to look for (ie "cwebp")
     *
     * @return array binaries found
     */
    public static function locateInstalledBinaries($binary)
    {
        if (stripos(PHP_OS, 'WIN') === 0) {
            $paths = self::locateBinariesUsingWhere($binary);
            if (count($paths) > 0) {
                return $paths;
            }
        } else {
            $paths = self::locateBinariesUsingWhich($binary);
            if (count($paths) > 0) {
                return $paths;
            }

            $paths = self::locateBinariesUsingWhereIs($binary);
            if (count($paths) > 0) {
                return $paths;
            }
        }
        return [];
    }
}
