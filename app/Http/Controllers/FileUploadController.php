<?php

namespace App\Http\Controllers;

use App\Helper\CaseStylesHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FileUploadController extends Controller
{
    use CaseStylesHelper;

    public function FileUploader(Request $request)
    {
        // Validation request
        $validator = Validator::make($request->all(), [
            'bucket' => ['required', 'string'],
            'files' => ['required', 'array', 'min:1', 'max:5'],
            'files.*' => ['required', 'max:5000']
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, $validator->getMessageBag()->first())->setStatusCode(Response::HTTP_BAD_REQUEST);
        }

        // check directory already created or not
        $path = public_path() . '/file-upload/' . $request->input('bucket');
        if (!file_exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
        }

        // save posted image into
        $files = $request->file('files');
        $res  = [];
        foreach ($files as $i => $file) {
            $name = $file->getClientOriginalName();
            $newName = rand(1, 1000000000) . '_' . $name;
            $file->move($path, $newName);
            $res['fileUrl'][$i] = \URL::to('/') . '/file-upload/' . $request->input('bucket') . '/' . $newName;
        }

        // return the response
        return $this->sendResponse(true, 'Ok', $res);
    }

}
