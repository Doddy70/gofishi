<?php

namespace App\Http\Controllers\Vendor;
use Illuminate\Support\Facades\File;

use App\Http\Controllers\Controller;
use App\Models\CaptainGallery;
use App\Http\Helpers\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CaptainGalleryController extends Controller
{
    public function index()
    {
        $vendorId = Auth::guard('vendor')->user()->id;
        $galleries = CaptainGallery::where('vendor_id', $vendorId)->orderBy('serial_number', 'asc')->get();
        return view('vendors.captain-gallery.index', compact('galleries'));
    }

    public function store(Request $request)
    {
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'title' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:100',
            'serial_number' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $imgName = UploadFile::store(public_path('assets/img/captain/gallery/'), $request->file('image'));

        CaptainGallery::create([
            'vendor_id' => Auth::guard('vendor')->user()->id,
            'image' => $imgName,
            'title' => $request->title,
            'weight' => $request->weight,
            'serial_number' => $request->serial_number
        ]);

        Session::flash('success', __('Big Catch photo uploaded successfully!'));
        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:100',
            'serial_number' => 'required|integer',
        ];

        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $gallery = CaptainGallery::where('vendor_id', Auth::guard('vendor')->user()->id)->findOrFail($request->id);

        if ($request->hasFile('image')) {
            $imgName = UploadFile::update(public_path('assets/img/captain/gallery/'), $request->file('image'), $gallery->image);
            $gallery->image = $imgName;
        }

        $gallery->title = $request->title;
        $gallery->weight = $request->weight;
        $gallery->serial_number = $request->serial_number;
        $gallery->save();

        Session::flash('success', __('Big Catch photo updated successfully!'));
        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $gallery = CaptainGallery::where('vendor_id', Auth::guard('vendor')->user()->id)->findOrFail($id);
        @unlink(public_path('assets/img/captain/gallery/') . $gallery->image);
        $gallery->delete();

        return redirect()->back()->with('success', __('Photo deleted successfully!'));
    }
}