<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Add authorization for admin later
        // For now, let's assume an admin can see all messages.
        // Gate::authorize('view-contact-messages');
        return ContactMessage::latest()->paginate(20);
    }

    public function store(StoreContactMessageRequest $request)
    {
        $message = ContactMessage::create($request->validated());
        return response()->json([
            'message' => 'Your message has been received successfully!'
        ], 201);
    }

}
