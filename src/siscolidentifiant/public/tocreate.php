<?php
$file=realpath(".")."/repris_juillet.csv";
if(file_exists($file)) {
    $source = fopen($file,"r+");
    $target = fopen("listeacreercomp.txt","a+");
    while($line = fgets($source)){
        if(substr($line, -2)!=";\n") {
            $line=substr($line,0,strlen($line)-1).";\n";
            echo "'".substr($line, -2)."'<br>";
        }
        fwrite($target,$line);
    }
    fclose($target);
    fclose($source);
}
?>
