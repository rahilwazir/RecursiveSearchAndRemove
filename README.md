# Instructions for using:
1. Modify the variable `$targetDirectory` to the directory path you want to scan, this should be relative to the directory 
in which this file resides.
2. Modiufy `$remove` accordingly. 
   ```sh 
    $remove = true; //This will search and remove the matching pattern.
    $remove = false; //This will only search and show the results for matching pattern.
    ```

3. The Target Extension for the files you want to find in your directory.
    ```sh 
      $targetExtension = '.php';
    ```
4. The Regex Pattern you want to find.
    ```sh 
     $pattern = '/(eval\(base64.*?\))\);/';
    ```
    
5. If you want to log the results in a file then make sure you create write permission for this file `eval_removal_log.html`
    ```sh 
     $logResults = true;
    ```
    
# Deafult use Of the script
    - By default, the script is written to find and replace Eval Base64 Decode or Encode recursively in directory.
    This can help you identify if there is any malicious code in your directory and remove it using this script.
    
# Custom use Of the script
    - This script can be modifed to search any other pattern by modifying the `$pattern` varaibale. You can use your own regex pattern here to find or replace
    the desired matching pattern