<?php

    if(php_sapi_name() != "cli")
    {
        header("Contente-type: text/plain");
        print("this tool is cli only.\n");
        print("please execute ` php ".__FILE__." ` or the sh/bat file providden.");
        exit();
    }

    echo chr(27).chr(91).'H'.chr(27).chr(91).'J';


    print("
\033[92mWelcome to the Block State Generator Tool.\033[39m

authors: Louis BERTRAND <contact@louisbertrand.studio>

This tool will help ou create multiple files for you to
add to your Mod/Ressourcepack Asset Folder.

if you create a vanilla ressource pack please use minecraft
as the mod id.

\033[93m[WARNING] to stop execution, combo ctrl+c or cmd+c\033[39m
\033[93m[WARNING] environment variables are located in .env and formatted
          as INI.\033[39m

");

    $readENV = parse_ini_file(__DIR__."/.env");

    if(isset($readENV["MOD_ID"]))
        {$modID = $readENV["MOD_ID"]; print "\033[93m[WARNING] using Environment Variable for Mod ID\033[39m\n";}
    else
        $modID = strtolower( readline("Mod ID (ex: minecraft, byg): ") );
    
    if(isset($readENV["BLOCK_ID"]))
        {$blockID = $readENV["BLOCK_ID"]; print "\033[93m[WARNING] using Environment Variable for Block ID\033[39m\n";}
    else
        $blockID = strtolower( readline("block ID (ex: acacia_door, block_iron_window): ") );
    
    if(isset($readENV["DOOR_HEIGHT_TWO"]))
        {$twoHeightDoor = $readENV["DOOR_HEIGHT_TWO"]; print "\033[93m[WARNING] using Environment Variable for Door Height\033[39m\n";}
    else
    {
        print("Do you wish to generate a 2 height door? (y/n - every input different than y will be considered as no)\n > ");
        $twoHeightDoor = strtolower( fgetc(STDIN));
    }
    

    $uid = md5(time());

    if(!is_dir(__DIR__."/output/"))
        mkdir(__DIR__."/output/");

    if($twoHeightDoor == "y")
    {

        $modelsDirPath = __DIR__."/templates/twoHeightDoor/models";
        $modelsDir = scandir($modelsDirPath);

        mkdir(__DIR__."/output/".$uid);
        mkdir(__DIR__."/output/".$uid."/blockstates");
        mkdir(__DIR__."/output/".$uid."/models");

        for ($i=0; $i < count($modelsDir); $i++) { 

            if($modelsDir[$i] == "." || $modelsDir[$i] == "..")
                continue;
            
            $readFile = file_get_contents($modelsDirPath."/".$modelsDir[$i]);

            $output = str_replace("%ModID%", $modID, $readFile);
            $output = str_replace("%BlockID%", $blockID, $output);

            file_put_contents(__DIR__."/output/".$uid."/models/".str_replace("__blockid_",$blockID,$modelsDir[$i]), $output);

        }

        $readBlockState = file_get_contents(__DIR__."/templates/twoHeightDoor/blockstates/__blockid__.json");

        $outputBlockState = str_replace("%ModID%", $modID, $readBlockState);
        $outputBlockState = str_replace("%BlockID%", $blockID, $outputBlockState);

        $outputPath = __DIR__."/output/".$uid."/blockstates/".$blockID.".json";
        file_put_contents($outputPath, $outputBlockState);

    }
    else
    {{

        $modelsDirPath = __DIR__."/templates/singleHeightDoor/models";
        $modelsDir = scandir($modelsDirPath);

        mkdir(__DIR__."/output/".$uid);
        mkdir(__DIR__."/output/".$uid."/blockstates");
        mkdir(__DIR__."/output/".$uid."/models");

        for ($i=0; $i < count($modelsDir); $i++) { 

            if($modelsDir[$i] == "." || $modelsDir[$i] == "..")
                continue;
            
            $readFile = file_get_contents($modelsDirPath."/".$modelsDir[$i]);

            $output = str_replace("%ModID%", $modID, $readFile);
            $output = str_replace("%BlockID%", $blockID, $output);

            file_put_contents(__DIR__."/output/".$uid."/models/".str_replace("__blockid_",$blockID,$modelsDir[$i]), $output);

        }

        $readBlockState = file_get_contents(__DIR__."/templates/singleHeightDoor/blockstates/__blockid__.json");

        $outputBlockState = str_replace("%ModID%", $modID, $readBlockState);
        $outputBlockState = str_replace("%BlockID%", $blockID, $outputBlockState);

        $outputPath = __DIR__."/output/".$uid."/blockstates/".$blockID.".json";
        file_put_contents($outputPath, $outputBlockState);

    }
    }

    print("
\033[92mGeneration Finished.\033[39m

your files are located in \033[92m'output/".$uid."'\033[39m.

You can now copy the children folders in your mod/ressource pack.
");
