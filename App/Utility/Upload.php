<?php

namespace App\Utility;

class Upload {


    public static function uploadFile($file, $fileName)
    {
        try{
            echo ('<script> alert("Cas 4") </script>');
            $currentDirectory = getcwd();
            echo ('<script> alert("Cas 5") </script>');
            $uploadDirectory = "/storage/";
            echo ('<script> alert("Cas 6") </script>');


            $fileExtensionsAllowed = ['jpeg', 'jpg', 'png'];
            echo ('<script> alert("Cas 7") </script>');

            $fileSize = $file['size'];
            echo ('<script> alert("Cas 8") </script>');
            $fileTmpName = $file['tmp_name'];
            echo ('<script> alert("Cas 9") </script>');

            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            echo ('<script> alert("Cas 10") </script>');
            $pictureName = basename($fileName . '.' . $fileExtension);
            echo ('<script> alert("Cas 11") </script>');


            $uploadPath = $currentDirectory . $uploadDirectory . $pictureName;
            echo ('<script> alert("Cas 12") </script>');

            if ($fileExtension == "") {
                echo ('<script> alert("Cas 3") </script>');
            } else {
                if (!in_array($fileExtension, $fileExtensionsAllowed)) {
                    echo ('<script> alert("Cas 1") </script>');
                    throw new \Exception("This file extension is not allowed. Please upload a JPEG or PNG file");
                }

                if ($fileSize > 4000000) {
                    echo ('<script> alert("Cas 2") </script>');
                    throw new \Exception("File exceeds maximum size (4MB)");
                }

                echo ('<script> alert("Cas 15") </script>');
                var_dump($fileTmpName);
                var_dump($uploadPath);
                $didUpload = move_uploaded_file($fileTmpName, $uploadPath);
                echo ('<script> alert("Cas 16") </script>');

                if ($didUpload) {
                    echo ('<script> alert("Cas 13") </script>');
                    return $pictureName;
                } else {
                    echo ('<script> alert("Cas 14") </script>');
                    throw new \Exception("An error occurred. Please contact the administrator.");
                }
            }
        }
        catch
        (\Exception $e){
            echo ('<script> alert("Cas 17 : " . $e) </script>');
            throw new \Exception("Une erreur c'est produite lors de l'envoi de l'image : " .$e);
        }


    }
}
