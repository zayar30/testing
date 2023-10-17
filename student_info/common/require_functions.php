<?php
    function formatDateString($dateString) {
   // Create a DateTime object from the input datetime string
    $dateTimeObj = DateTime::createFromFormat('m/d/Y', $dateString);

   // Format the DateTime object to the desired format (d/m/Y H:i:s)
    $formattedDateTime = $dateTimeObj->format('Y-m-d');

   // Return the formatted datetime
   return $formattedDateTime;

    }
    function formatmdY($dateString){
        // Create a DateTime object from the input datetime string
        $dateTimeObj = DateTime::createFromFormat('Y-m-d', $dateString);

    // Format the DateTime object to the desired format (d/m/Y H:i:s)
         $formattedDateTime = $dateTimeObj->format('m/d/Y');
 
    // Return the formatted datetime
        return $formattedDateTime;

    }
    function getfullImage($upload_file,$id){
        $full_image = "upload/".$id."/"."$upload_file";
        return $full_image;
    }
    ?>