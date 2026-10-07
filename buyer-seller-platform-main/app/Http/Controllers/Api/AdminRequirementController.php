<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Requirement;
use Illuminate\Http\Request;

class AdminRequirementController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $financing = trim((string) $request->get('financing', ''));
        $gstStatus = trim((string) $request->get('gst_status', ''));

        $query = Requirement::query()
            ->with([
                'user:id,name,email,phone',
            ])
            ->latest('id');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('product_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($uq) use ($q) {
                        $uq->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%");
                    });
            });
        }

        if ($financing !== '') {
            $query->where('financing', $financing);
        }

        if ($gstStatus !== '') {
            $query->where('gst_status', $gstStatus);
        }

        $rows = $query->paginate(20);

        $rows->getCollection()->transform(function ($req) {
            return [
                'id' => $req->id,
                'product_name' => $req->product_name,
                'financing' => $req->financing,
                'gst_status' => $req->gst_status,
                'voice_recording' => $req->voice_recording,
                'created_at' => optional($req->created_at)?->toISOString(),

                'user_id' => $req->user?->id,
                'user_name' => $req->user?->name,
                'user_email' => $req->user?->email,
                'user_phone' => $req->user?->phone,
            ];
        });

        return response()->json($rows);
    }

    public function show($id)
    {
        $req = Requirement::query()
            ->with([
                'user:id,name,email,phone',
            ])
            ->findOrFail($id);

        return response()->json([
            'id' => $req->id,
            'product_name' => $req->product_name,
            'financing' => $req->financing,
            'gst_status' => $req->gst_status,
            'voice_recording' => $req->voice_recording,
            'created_at' => optional($req->created_at)?->toISOString(),
            'user' => [
                'id' => $req->user?->id,
                'name' => $req->user?->name,
                'email' => $req->user?->email,
                'phone' => $req->user?->phone,
            ],
        ]);
    }
}