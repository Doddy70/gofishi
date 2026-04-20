<?php

namespace App\Http\Controllers\Admin\LokasiManagement;

use App\Http\Controllers\Controller;
use App\Models\Amenitie;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\HotelContent;
use App\Models\RoomContent;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AmenitieController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->first() ?: Language::where('is_default', 1)->first() ?: Language::first();
        $information['amenities'] = $language->amenitieInfo()->orderByDesc('id')->get();
        $information['langs'] = Language::all();
        $information['language'] = $language;

        return view('admin.lokasi-management.amenitie.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'icon' => 'required',
            'title' => [
                'required',
                Rule::unique('amenities')->where(function ($query) use ($request) {

                    return $query->where('language_id', $request->input('language_id'));
                }),
                'max:255',
            ],
        ];

        $message = [
            'language_id.required' => __('The language field is required.')
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }
        Amenitie::query()->create($request->except('language'));

        Session::flash('success', __('Amenitie stored successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => [
                'required',
                Rule::unique('amenities')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->input('language_id'));
                })->ignore($request->id, 'id'),
                'max:255',
            ],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $aminiteInfo = Amenitie::query()->find($request->id);

        $aminiteInfo->update($request->except('language'));

        Session::flash('success', __('Aminite updated successfully') . '!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $this->detachAmenityFromContents($id);

        $aminiteInfo = Amenitie::query()->find($id);
        if ($aminiteInfo) {
            $aminiteInfo->delete();
        }

        return redirect()->back()->with('success',  __('Amenitie deleted successfully') . '!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request['ids'];
        foreach ($ids as $id) {
            $this->detachAmenityFromContents($id);
            $aminiteInfo = Amenitie::query()->find($id);
            if ($aminiteInfo) {
                $aminiteInfo->delete();
            }
        }

        Session::flash('success', __('Selected Informations deleted successfully') . '!');
        return Response::json(['status' => 'success'], 200);
    }

    private function detachAmenityFromContents($id): void
    {
        $hotels = HotelContent::select('id', 'amenities')->get();
        foreach ($hotels as $hotel) {
            $amenities = $this->normalizeAmenities($hotel->amenities);
            if (in_array($id, $amenities)) {
                $amenities = array_values(array_diff($amenities, [$id]));
                $hotel->amenities = json_encode($amenities);
                $hotel->save();
            }
        }

        $rooms = RoomContent::select('id', 'amenities')->get();
        foreach ($rooms as $room) {
            $amenities = $this->normalizeAmenities($room->amenities);
            if (in_array($id, $amenities)) {
                $amenities = array_values(array_diff($amenities, [$id]));
                $room->amenities = json_encode($amenities);
                $room->save();
            }
        }
    }

    private function normalizeAmenities($value): array
    {
        $decoded = json_decode($value, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_filter($decoded, function ($item) {
            return $item !== null && $item !== '';
        }));
    }
}
