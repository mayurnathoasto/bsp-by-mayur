<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class UserSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $items = SupportTicket::query()
            ->with([
                'targetUser:id,name,email,phone,seller_status',
                'adminRepliedBy:id,name,email',
            ])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(10);

        $targetUser = null;
        $targetUserId = (int) $request->query('target_user_id', 0);

        if ($targetUserId > 0) {
            $targetUser = User::query()
                ->select('id', 'name', 'email', 'phone', 'seller_status', 'company_name')
                ->find($targetUserId);
        }

        return view('user.support_tickets', [
            'tickets' => $items,
            'prefillType' => in_array($request->query('type'), [SupportTicket::TYPE_TICKET, SupportTicket::TYPE_REPORT], true)
                ? $request->query('type')
                : SupportTicket::TYPE_TICKET,
            'targetUser' => $targetUser,
            'contextUrl' => $request->query('context_url'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'string', 'in:ticket,report'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:5000'],
            'order_reference' => ['nullable', 'string', 'max:120'],
            'target_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'context_url' => ['nullable', 'string', 'max:500'],
        ]);

        SupportTicket::create([
            'user_id' => $request->user()->id,
            'target_user_id' => $data['target_user_id'] ?? null,
            'type' => $data['type'],
            'status' => SupportTicket::STATUS_OPEN,
            'order_reference' => $data['order_reference'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'context_url' => $data['context_url'] ?? null,
        ]);

        return back()->with('success', 'Your request has been submitted successfully.');
    }
}
