<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\DataTransferObjects\PhotoDto;

interface PhotoInterface {
    public function getAllPhotos(Request $request);
	public function storePhoto(PhotoDto $request);
    public function getPhotoById($id);
    public function updatePhoto(PhotoDto $request,$id);
    public function deletePhoto($id);
}
