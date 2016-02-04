/*exec("cp create_env createenv");

$zip = new ZipArchive;
$res = $zip->open('file.zip');

if ($res === TRUE) {
	$zip->extractTo(getcwd());
	$zip->close();

	echo 'woot!';
} 
else {
	echo 'doh!';
}
*/

// admin 
// local
if (!file_exists("v3ctorwh"))
	mkdir("v3ctorwh");
else
	echo "App already exists";

$myfile = fopen("v3ctorwh/newfile.txt", "w") or die("Unable to open file!");
$txt = '<?php' . "\n";
fwrite($myfile, $txt);

$txt = '$hostname = "localhost";' . "\n";
fwrite($myfile, $txt);
$txt = '$username = "miuser";' . "\n";
fwrite($myfile, $txt);
fclose($myfile);

chmod ("v3ctorwh/newfile.txt", 0750);