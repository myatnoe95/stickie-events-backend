<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

class Utility
{
	public static function GetBase64FromDataUri($data_uri)
	{
		$commaPos = strpos($data_uri, ',');

		if ($commaPos !== false && $commaPos < strlen($data_uri) - 1)
		{
			$base64 = substr($data_uri, $commaPos + 1);
			return $base64;
		}

		return null;
	}

    public static function GetFileTypeFromDataUri($data_uri)
	{
		$matches = array();
    	preg_match('/data:([\w\/.-]+);/', $data_uri, $matches);

		if (isset($matches[1])) return $matches[1];

		return null;
	}

    public static function processBase64Image($data_uri, $folder_name, $index = null)
	{    

		$base64 = self::GetBase64FromDataUri($data_uri);
		$file_type = self::GetFileTypeFromDataUri($data_uri);
		
		if ($base64 === null || $file_type === null) {
			return null;
		}

		$base64_decoded = base64_decode($base64);

		$mime_to_extension = [
			'image/jpeg' => 'jpg',
			'image/png' => 'png',
			'image/jpg' => 'jpg',
		];

		$extension = $mime_to_extension[$file_type] ?? null;

		if ($extension === null) {
			return null;
		}

		$fileName = time() . ($index !== null ? '_' . $index : '') . '.' . $extension;

		$disk = 'public';
		$folder = 'images/' . $folder_name;

		$filePath = $folder . '/' . $fileName;
		
		Storage::disk($disk)->put($filePath, $base64_decoded);

		return 'storage/' . $filePath;
}

}


