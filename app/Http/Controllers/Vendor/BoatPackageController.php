<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BoatPackage;
use App\Models\Perahu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BoatPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($perahu_id)
    {
        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id) {
            abort(403);
        }

        $packages = $perahu->packages;

        return view('vendors.perahu.packages.index', compact('perahu', 'packages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($perahu_id)
    {
        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id) {
            abort(403);
        }

        return view('vendors.perahu.packages.create', compact('perahu'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $perahu_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer|min:1',
            'area' => 'nullable|string|max:255',
            'meeting_time' => 'nullable|date_format:H:i',
            'return_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id) {
            abort(403);
        }

        $perahu->packages()->create($request->all());

        Session::flash('success', 'Package created successfully!');
        return redirect()->route('vendor.perahu.packages.index', ['perahu_id' => $perahu_id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($perahu_id, BoatPackage $package)
    {
        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat and the package belongs to the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id || $package->room_id != $perahu->id) {
            abort(403);
        }

        return view('vendors.perahu.packages.edit', compact('perahu', 'package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $perahu_id, BoatPackage $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer|min:1',
            'area' => 'nullable|string|max:255',
            'meeting_time' => 'nullable|date_format:H:i',
            'return_time' => 'nullable|date_format:H:i',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat and the package belongs to the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id || $package->room_id != $perahu->id) {
            abort(403);
        }

        $package->update($request->all());

        Session::flash('success', 'Package updated successfully!');
        return redirect()->route('vendor.perahu.packages.index', ['perahu_id' => $perahu_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($perahu_id, BoatPackage $package)
    {
        $perahu = Perahu::findOrFail($perahu_id);

        // Ensure the authenticated vendor owns the boat and the package belongs to the boat
        if ($perahu->vendor_id != Auth::guard('vendor')->user()->id || $package->room_id != $perahu->id) {
            abort(403);
        }

        $package->delete();

        Session::flash('success', 'Package deleted successfully!');
        return redirect()->route('vendor.perahu.packages.index', ['perahu_id' => $perahu_id]);
    }
}
