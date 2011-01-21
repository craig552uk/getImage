<?php
/*******************************************************************************
Author:     Craig Russell
Email:      craig@craig-russell.co.uk
Web:        www.craig-russell.co.uk
Copyright:  Puppy Image by Craig Russell

Simple image library for serving up image files at different resolutions.
Useful for serving images for responsive websites.


- HOW TO USE -

Image files are defined in the $images array like

$images['id']['scale'] = "file";

    id      A URL safe identifier for the image
    scale   A percentage scale of the image file
            100 is fullsize image
            0   is default image
            Define as many intermediate images as you want
    file    The name of the image file

Images are reqested like
http://localhost/getimage.php?id=html5logo&scale=100

*******************************************************************************/
// Image scale definitions
$images['puppy'][100]      = 'puppy_1280x960.jpg';
$images['puppy'][75]       = 'puppy_960x720.jpg';
$images['puppy'][50]       = 'puppy_640x480.jpg';
$images['puppy'][25]       = 'puppy_320x240.jpg';
$images['puppy'][10]       = 'puppy_128x96.jpg';
$images['puppy'][0]        = 'clear_pixel.png';     // Default image

// Images folder (with trailing slash)
$imagepath = "images/";


/*******************************************************************************
        Functions
*******************************************************************************/

/*
*    Search through image array for next image equal to or above requested scale
*    
*    @param  array   Array of image data
*    @param  int     Requested scale
*/
function getImageScale($images, $scale){
    for($i=$scale; $i<=100; $i++){
        if (isset($images[$i])) { return $images[$i]; }
    }
    return false;
}

/*
*    Gets an image file and displays it in the browser
*    
*    @param  string  Local file path
*/
function displayImage($filename){
    
    // Get image meta and image file handle
    $meta = getimagesize($filename);
    $image = fopen($filename, "rb");
    
    if ($meta && $image) {
        header("Content-type: ".$meta['mime']);
        fpassthru($image);
        exit;
    }
}


/*******************************************************************************
        The Main Code Block
*******************************************************************************/

// Get query string values
$image_id       = (isset($_GET['id'])) ? $_GET['id'] : "";
$image_scale    = (isset($_GET['scale'])) ? $_GET['scale'] : "0"; // Default to smallest image

// If image is defined in $images array
if (isset($images[$image_id])){

    // Get file name for requested scale
    $image_file = getImageScale($images[$image_id], $image_scale);
    
    // If image file exists
    if($image_file && file_exists($imagepath.$image_file)){
        // Display image
        displayImage($imagepath.$image_file);
    }
}else{
    echo "Image with ID \"$image_id\" is undefined";
}


