<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Collaborator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CollaboratorController extends Controller
{
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $collaborators = Collaborator::with('user')->where('vendor_id', $vendor->id)->get();
        return view('vendors.collaborators.index', compact('collaborators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:co-host,editor,viewer'
        ]);

        $vendor = Auth::guard('vendor')->user();
        $user = User::where('email', $request->email)->first();

        if ($user->id === $vendor->id) {
            Session::flash('warning', __('Anda tidak dapat menambahkan diri sendiri sebagai kolaborator.'));
            return redirect()->back();
        }

        Collaborator::updateOrCreate(
            ['vendor_id' => $vendor->id, 'user_id' => $user->id],
            ['role' => $request->role, 'permissions' => $request->permissions]
        );

        Session::flash('success', __('Kolaborator berhasil ditambahkan!'));
        return redirect()->back();
    }

    public function destroy($id)
    {
        $vendor = Auth::guard('vendor')->user();
        $collaborator = Collaborator::where('id', $id)->where('vendor_id', $vendor->id)->firstOrFail();
        $collaborator->delete();

        Session::flash('success', __('Kolaborator berhasil dihapus.'));
        return redirect()->back();
    }
}
