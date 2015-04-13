<?php

    /**
     * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
     * CC-BY License - http://creativecommons.org/licenses/by/3.0/
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function gzip($sPath, $sOutFile)
    {
        //$sPath = '/opt/lampp/htdocs/vignesh/dropinn-1.6.6/js/home_new.js';
//$sOutFile = $sPath.'.gz';

        if (!file_exists($sOutFile))
        {
// Open the gz file (w9 is the highest compression)
            $fp = gzopen($sOutFile, 'w9');

// Compress the file
            gzwrite($fp, file_get_contents($sPath));

// Close the gz file and we are done
            gzclose($fp);
        }
        $filename = $sOutFile;
        $zd = GZOPEN($filename, "r");
        $contents = GZREAD($zd, 10000);
        GZCLOSE($zd);

        return $contents;
    }
    