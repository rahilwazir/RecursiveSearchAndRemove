<?php
# RegEx For Eval Removal: \s+(eval\(base64.*?\))\);
exit;
function back_to_for_slash($str) {
    return str_replace('\\', '/', $str);
}

$current_dir = back_to_for_slash(dirname(__FILE__) . '/public_html');
$directory = $current_dir;

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
echo "Eval Base64 Decode or Encode Removal Script\n-----------";
$i = 1;

// Concatenated end message for logging
$allEndMsg = '';

// End message for logging
$endMsg = '';

// Find and replace, default to Find only
$remove = false;

// Targeted file extension to find occurences
$targetExtension = '.php';

while ($it->valid()) {

    // If its not parent or current
    if (!$it->isDot()) {

        // If $targetExtension file is found
        if (strpos($it->key(), $targetExtension) !== false) {

            // Windows OS only
            $file_name = back_to_for_slash($it->key());

            if(!is_writable($file_name)) {
                $endMsg = 'Key: ' . $file_name . ' <span style="color: yellow;"><strong>File is not writable or doesn\'t exist.</strong></span><br>';
            } else {
                $file = fopen($file_name, 'r');
                if (filesize($file_name) > 0) {
                    $contents = fread($file, filesize($file_name));
                    if ( $contents !== false ) {
                        fclose($file);
                        $pattern = '/(eval\(base64.*?\))\);/';
                        preg_match($pattern, $contents, $matches);

                        if ( sizeof($matches) > 0 ) {
                            if ($remove === true) {
                                $content = preg_replace($pattern, '', $contents);
                                $file2 = fopen($file_name, 'w+');
                                $fwrite = fwrite($file2, $content);
                                if ( $fwrite !== false ) {
                                    fclose($file2);
                                    $endMsg = 'Key: ' . $file_name . ' <span style="color: green;"><strong>Found and Removed.</strong></span><br>';
                                } else {
                                    $endMsg = 'Key: ' . $file_name . ' <span style="color: yellow;"><strong>Couldn\'t write to the file.</strong><br>';
                                }
                            } else {
                                $endMsg = "Key: " . $file_name . " Found\n";
                            }
                        } else {
                            $endMsg = 'Key: ' . $file_name . ' <span style="color: blue;"><strong>No Eval Code found.</strong></span><br>';
                        }
                    } else {
                        $endMsg = 'Key: ' . $file_name . '<span style="color: yellow;"> <strong>Can\'t read the file..</strong></span><br>';
                    }
                } else {
                    $endMsg = 'Key: ' . $file_name . '<span style="color: yellow;"> <strong>File is empty.</strong></span><br>';
                }
            }

            $allEndMsg .= $endMsg;
            echo $endMsg;
        }
    }
    $i++;
    $it->next();
}

$logFile = fopen(dirname(__FILE__) . '/eval_removal_log.html', 'w');
$logFileFwrite = fwrite($logFile, $allEndMsg);
if ( $logFileFwrite !== false ) fclose($logFile);
