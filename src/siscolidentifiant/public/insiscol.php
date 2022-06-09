<?php
$file=realpath(".")."/listesiscol.txt";
if(file_exists($file)) {
    $source = fopen($file,"r+");
    $target = fopen("listesiscolcomp.txt","a+");
    while($line = fgets($source)){
        if(strpos($line,":")) {
            $sapid=substr($line, 0,strpos($line,":"));
            $name=substr($line, strpos($line,":"));
            $sapid = str_repeat("0",10-strlen($sapid)).$sapid;
            //echo $sapid.$name."<br>";
            fwrite($target,$sapid.$name);
        }
    }
    fclose($target);
    fclose($source);
}
?>
