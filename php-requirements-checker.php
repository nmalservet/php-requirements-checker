<html><body>
        <?php
        /**
         * This script will ccheck if requirements defined into the file my-requirements are good.
         * It can display the result into the console or into an html format
         * @author nicolas malservet
         */
        include ('my-requirements.php');

        /**
         * check if the libraries are missing
         * @return an array of the result ["TYPE","dependency","status","message","ERROR_CRITICALITY"]
         */
        function checkLibraries($libraries_required) {
            $result = [];
            foreach ($libraries_required as $library) {
                $name = $library[1];
                $version = $library[2];
                $compatMode = $library[3];
                $level = $library[4];
                //check if the library exists
                $status = "ko";
                $message = "library not loaded";
                //if name = PHP_VERSION
                if($name=="PHP_VERSION"){
                    $cversion = phpversion();
                    $comp= version_compare ($version ,$cversion);
                    if($comp==-1){
                        //case expected version < current version
                        $status="ok";
                        $message = "PHP version ".$version." expected, version ".$cversion." founded";
                    }
                    if($comp==0){
                        $message = "PHP version ".$version." expected is founded";
                        $status="ok";
                    }
                    if($comp==1){
                        $message = "PHP version ".$version." expected, version ".$cversion." founded";
                        $status="ko";
                    }
                        
                }else
                if (extension_loaded($name)) {
                    $status = "ok";
                    $message = "library loaded";
                    //get version
                    $cversion = phpversion($name);
                    if($version==$cversion){
                        $message.=", equal version ";
                    }else{
                        $comp= version_compare ($version ,$cversion);
                        $message.=", version ".$version." expected , version ".$cversion." founded";
                        if($comp==-1){
                            //case expected version < current version
                            if($compatMode=="asc"){
                                $status = "ko";
                            }
                            if($compatMode=="desc"){
                                $status = "ok";
                            }
                        }
                        if($comp==0){
                            //cas version == cversion
                            $message.=", equal version ";
                        }
                        if($comp==1){
                            //case expected version > current version
                            if($compatMode=="asc"){
                                $status = "ok";
                            }
                            if($compatMode=="desc"){
                                $status = "ko";
                            }
                        }
                    }
                    
                    
                }
                //check if the version is correct
                $dependency = $name . " " . $version;
                $result[] = ["LIBRARY", $dependency, $status, $message, $level];
            }
            return $result;
        }

        /**
         * check if thefolders are accessibles
         *  @param  The expected syntax is the following
         *   ["FOLDER_NAME","ACCESS","ERROR_CRITICALITY"],
         * example
         *  ["test/","R","WARN"],
         * @return an array of the result ["TYPE","FOLDER_NAME","status","message","ERROR_CRITICALITY"]
         */
        function checkFolders($folders_required) {
            $result = [];
            foreach ($folders_required as $folder) {
                $name = $folder[0];
                $access = $folder[1];
                $level = $folder[2];
                //check if the folder exists
                //prepare the message
                $status = "ko";
                $message = "folder inexisting";
                if (is_dir($name)) {
                    $message = "folder exist";
                    $status = "ok";
                }
                $result[] = ["FOLDER", $name, $status, $message, $level];
            }
            return $result;
        }

        /**
         * check if thefolders are accessibles
         *  @param  The expected syntax is the following
         *   ["FOLDER_NAME","ACCESS","ERROR_CRITICALITY"],
         * example
         *  ["test/","R","WARN"],
         * @return an array of the result ["TYPE","FOLDER_NAME","status","message","ERROR_CRITICALITY"]
         */
        function checkFiles($files_required) {
            $result = [];
            foreach ($files_required as $file) {
                $name = $file[0];
                $access = $file[1];
                $level = $file[2];
                $status = "ko";
                $message = "file inexisting";
                if (is_file($name)) {
                    $message = "file exist";
                    $status = "ok";
                }
                $result[] = ["FILE", $name, $status, $message, $level];
            }
            return $result;
        }

        /**
         * check all the requirements
         * @return an array of the result ["TYPE","dependency","status","message","ERROR_CRITICALITY"]
         */
        function checkAll($libraries_required, $folders_required, $files_required) {
            $result = checkLibraries($libraries_required);
            $result = array_merge($result, checkFolders($folders_required));
            $result = array_merge($result, checkFiles($files_required));
            return $result;
        }

        /**
         * print the result into an html format
         */
        function toHTML($libraries_required, $folders_required, $files_required) {
            $result = checkAll($libraries_required, $folders_required, $files_required);
            $html = "<head>
                    <style>
                    :root{
                     --warn: #FFA07A;
                    --error: #F08080;
                    --notice: #B0C4DE;
                    }

                    table {border-collapse: collapse;width: 100%;}
                    th, td {text-align: left;padding: 8px;}
                    tr:nth-child(even){background-color: #f2f2f2}

                    </style>
                    </head><table stle=\"\">";
            $html.="<tr><th>Status</th><th>Type</th><th>Name</th><th>Message</th></tr>";
            foreach ($result as $line) {
                $status = $line[2];
                $level = $line[4];
                $html.= "<tr><td style=\"" . ($status == "ok" ? "background-color:#90EE90;" : "background-color:var(--" . $level . ");") . "\">" . $status . "</td><td>" . $line[0] . "</td><td>" . $line[1] . "</td><td>" . $line[3] . "</td></tr></div>";
            }
            $html.="</table>";
            return $html;
        }

        /**
         * print the result for a command-line call
         */
        function toString() {
            
        }

        echo toHTML($libraries_required, $folders_required, $files_required);
        ?>

    <body></html>