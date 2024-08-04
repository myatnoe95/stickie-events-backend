<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Helpers\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Photo;
use App\Interfaces\PhotoInterface;
use App\Transformers\PhotoResource;
use App\DataTransferObjects\PhotoDto;

class PhotoRepository implements PhotoInterface{

    public function getAllPhotos(Request $request)
    {
        $columns = ['status'];
        $pageIndex = $request->input('pageIndex');
        $length = $request->input('pageSize');

		$start = $length * $pageIndex - $length;
        $search = $request->input('query');

		$query = Photo::with('user', 'event');

        if (!empty($search)) {
            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
			$query->orWhereHas('user', function ($q) use ($search) {
				$q->where('user_name', 'like', '%' . $search . '%');
			});

			$query->orWhereHas('event', function ($q) use ($search) {
				$q->where('event_name', 'like', '%' . $search . '%');
			});
	
        }

        $totalRecords = $query->count();

		if (!empty($length)) {
            $query->offset($start);
        }
        $query->limit($length);

        $data = $query->get();
        $result = [
            'totalRecords'  => $totalRecords,
            'data'          => $data,
        ];

        return $result;
    }

	public function storePhoto(PhotoDto $request)
    {
		$authUserId = auth()->user()->id;
		$photos = $request->photo_url;
   
        $index = 1;
        $savedPhotos = [];

		foreach ($photos as $photo) {

			$filePath = Utility::processBase64Image($photo , 'events', $index);

			if ($filePath === false) {
                return response()->json(['message' => 'Invalid image format or type'], 400);
            }

            $index++;

			$photo     = new Photo();
			$photo->fill([
				'user_id'    =>  $authUserId,
				'event_id'   =>  $request->event_id,
				'photo_url'  =>  $filePath,
			])->save();

            $savedPhotos[] = $photo->load(['user', 'event']);
		}

        return $savedPhotos;
    }

    public function getPhotoById($id)
    {
        $photo = Photo::where('id', $id)->first();

		return $photo == null ? null : $photo;
    }

	public function updatePhoto(PhotoDto $request, $id)
    {
        $photo = Photo::find($id);

        if ($photo == null) {
            return null;
        }

        $photoDBT = DB::transaction(function () use ($photo, $request) {
            $photo->fill($request->toArray())->save();
            return $photo;
        });

        return $photoDBT;
    }

    public function deletePhoto($id)
    {
		$photo = Photo::find($id);

		if ($photo == null) {
			return null;
		}

		$photoDBT = DB::transaction(function () use ($photo) {
			$photo->delete();
			return $photo;
		});

		return $photoDBT;
    }

    function getEventPhotos(Request $request)
    {
        $event_id = $request->input('event_id');
        $user_id = $request->input('user_id');

        $photos = Photo::where('event_id', $event_id)
            ->where('user_id', $user_id)
            ->get();

        return $photos;
    }
}
