<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $type = trim((string) $request->query('type', ''));
        $status = trim((string) $request->query('status', ''));
        $search = trim((string) $request->query('search', ''));

        $query = SupportTicket::query()
            ->with([
                'user:id,name,email,phone,seller_status',
                'targetUser:id,name,email,phone,seller_status',
                'adminRepliedBy:id,name,email',
            ])
            ->latest('id');

        if (in_array($type, [SupportTicket::TYPE_TICKET, SupportTicket::TYPE_REPORT], true)) {
            $query->where('type', $type);
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('order_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('targetUser', function ($targetQuery) use ($search) {
                        $targetQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        return response()->json($query->paginate(15));
    }

    public function show(SupportTicket $supportTicket)
    {
        $supportTicket->load([
            'user:id,name,email,phone,seller_status',
            'targetUser:id,name,email,phone,seller_status',
            'adminRepliedBy:id,name,email',
        ]);

        return response()->json($supportTicket);
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:open,under_review,resolved,closed'],
            'admin_reply' => ['required', 'string', 'max:5000'],
        ]);

        $supportTicket->status = $data['status'];
        $supportTicket->admin_reply = $data['admin_reply'];
        $supportTicket->admin_replied_by = $request->user()->id;
        $supportTicket->admin_replied_at = now();
        $supportTicket->save();

        return response()->json([
            'message' => 'Support case updated successfully.',
        ]);
    }
}
