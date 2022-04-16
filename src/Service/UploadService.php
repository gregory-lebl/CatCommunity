<?php

namespace App\Service;

use App\Entity\Image;
use Cocur\Slugify\Slugify;
use Symfony\Component\Security\Core\User\UserInterface;

class UploadService
{
    /**
     * Upload l'image d'un utilisateur sur le serveur
     * @param UserInterface $user
     * @param $file $_FILES
     * @param string $imageTitle
     * @return Image|false
     */
    public function uploadCatImage(UserInterface $user, $file, string $imageTitle): Image|false
    {
        $mimeTypesAccepted = ["image/jpeg","image/png","image/jpg","image/webp"];

        if (in_array($file["type"],$mimeTypesAccepted) === false){
            return false;
        }else{
            $errorCode = $file["error"];
            $size = $file["size"];
            $tmpName = $file["tmp_name"];
            $name = $file["name"];
            $extension = "." . pathinfo($name)["extension"];
            $newFileName = uniqid("cat") . $extension;

            if ($errorCode !== 0 || $size == 0){
                return false;
            }

            $uploadDirectory = $_SERVER["DOCUMENT_ROOT"] . "upload/cats/";
            if (!is_dir($uploadDirectory)){
                mkdir($uploadDirectory,0777,true);
            }

            $fullUploadPath = $uploadDirectory . $newFileName;

            if (move_uploaded_file($tmpName,$fullUploadPath)){
                $slugify = new Slugify();
                $image = new Image();

                $image->setTitle($imageTitle);
                $image->setSlug($slugify->slugify($imageTitle));
                $image->setUser($user);
                $image->setName($newFileName);

                return $image;
            }else{
                return false;
            }
        }
    }

}