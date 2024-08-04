<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseCodes;
use App\Interfaces\PhotoInterface;
use App\DataTransferObjects\PhotoDto;
use App\Transformers\PhotoResource;
use App\Transformers\PhotoCollection;

class PhotoController extends Controller
{
    private PhotoInterface $photoRepository;

    public function __construct(PhotoInterface $photoRepository)
    {
        $this->photoRepository = $photoRepository;
    }

    public function index(Request $request)
    {
        $photos = $this->photoRepository->getAllPhotos($request);

        if($photos == null){
            return $this->sendError($photos);
        }else{
            return $this->sendResponse(
                new PhotoCollection($photos['data']),
                ResponseCodes::OK,
                [
                    'total_records' => $photos['totalRecords']
                ]
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $photoDTO = PhotoDto::fromRequest($request);
            $photo = $this->photoRepository->storePhoto($photoDTO);

            if ($photo == null) {
                return $this->sendError($photo);
            }

            return $this->sendResponse(
                PhotoResource::collection($photo),
                ResponseCodes::CREATED, [
                    'message' => "Event Photo created successfully!"
                ]
            );
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $photo = $this->photoRepository->getPhotoById($id);
 
        if ($photo == null) {
            return $this->sendError($photo);
        }
        return $this->sendResponse(new PhotoResource($photo));
    }

    public function update(Request $request, $id)
    {
        try {
            $photoDTO = PhotoDto::fromRequest($request,true);
            $photo = $this->photoRepository->updatePhoto($photoDTO, $id);

            if ($photo == null) {
                return $this->sendError($photo);
            }
            return $this->sendResponse(new PhotoResource($photo),
            ResponseCodes::CREATED,[
                'message' =>  "Event Photo updated successfully!"
            ]);
        }
        catch (ValidationException $e) {
            return $this->sendError($e->errors(), ResponseCodes::UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            return $this->sendError($e->getMessage(), ResponseCodes::INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        $photo = $this->photoRepository->deletePhoto($id);
        if ($photo == null) {
            return $this->sendError($photo);
        }
        return $this->sendResponse(new PhotoResource($photo));
    }

    public function getEventPhotos(Request $request)
    {
        $photos = $this->photoRepository->getEventPhotos($request);
        return $photos;
        if($photos == null){
            return $this->sendError($photos);
        }else{
            return $this->sendResponse(
                new PhotoCollection($photos['data']),
                ResponseCodes::OK,
                [
                    'total_records' => $photos['totalRecords']
                ]
            );
        }
    }
}
