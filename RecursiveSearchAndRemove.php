<?php
/**
*  Script for Recursively Find and Replace Regex Pattern in a directory
*/

// Directory you want to Searhc
$targetDirectory = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'my_target_folder';

// Find and replace, default to Find only
$remove = false;

// Targeted file extension to find occurences
$targetExtension = '.php';

// The Pattern you would like to search
$pattern = '/(eval\(base64.*?\))\);/';

// Log Results in a file
$logResults = false;

// Concatenated end message for logging
$allEndMsg = '';

// End message for logging
$endMsg = '';


/******************************************************** Code Starts Here ********************************************************/

$directory = $targetDirectory;
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
echo "<h3>----------- Eval Base64 Decode or Encode Removal Script\n-----------</h3><br />";
$i = 1;

while ($it->valid()) {

    // If its not parent or current
    if (!$it->isDot()) {

        // If $targetExtension file is found
        if (strpos($it->key(), $targetExtension) !== false) {

            // Windows OS only
            $file_name = DIRECTORY_SEPARATOR . $it->key();

            if(!is_writable($file_name)) {
                $endMsg = 'Key: ' . $file_name . '  =====>  '. ' <span style="color: red;"><strong>File is not writable or doesn\'t exist.</strong></span><br>';
            } else {
                $file = fopen($file_name, 'r');
                if (filesize($file_name) > 0) {
                    $contents = fread($file, filesize($file_name));
                    if ( $contents !== false ) {
                        fclose($file);
                        preg_match($pattern, $contents, $matches);

                        if ( sizeof($matches) > 0 ) {
                            if ($remove === true) {
                                $content = preg_replace($pattern, '', $contents);
                                $file2 = fopen($file_name, 'w+');
                                $fwrite = fwrite($file2, $content);
                                if ( $fwrite !== false ) {
                                    fclose($file2);
                                    $endMsg = 'Key: ' . $file_name . '  =====>  '. ' <span style="color: green;"><strong>Pattern found and Removed.</strong></span><br>';
                                } else {
                                    $endMsg = 'Key: ' . $file_name . '  =====>  '. ' <span style="color: green;"><strong>Pattern found but not configured to remove matching pattern from the file.</strong><br>';
                                }
                            } else {
                                $endMsg = "Key: " . $file_name . '  =====>  '. " Found\n";
                            }
                        } else {
                            $endMsg = 'Key: ' . $file_name . '  =====>  '. ' <span style="color: blue;"><strong> Couldn\'t find the pattern.</strong></span><br>';
                        }
                    } else {
                        $endMsg = 'Key: ' . $file_name . '  =====>  '. '<span style="color: red;"> <strong>Can\'t read the file..</strong></span><br>';
                    }
                } else {
                    $endMsg = 'Key: ' . $file_name . '  =====>  '. '<span style="color: red;"> <strong>File is empty.</strong></span><br>';
                }
            }

            $allEndMsg .= $endMsg;
            echo $endMsg;
        }
    }
    $i++;
    $it->next();
}

if($logResults) {
    $logFile = fopen(dirname(__FILE__) . '/eval_removal_log.html', 'w');
    $logFileFwrite = fwrite($logFile, $allEndMsg);
    if ( $logFileFwrite !== false ) fclose($logFile);
}
