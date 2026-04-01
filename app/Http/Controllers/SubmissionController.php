<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public function create()
    {
        return Inertia::render('Submissions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Submission::create($validated);

        return redirect()->route('submissions.create')
            ->with('success', 'Submission sent successfully!');
    }

    public function index()
    {
        $submissions = Submission::latest()->paginate(15);

        return Inertia::render('Admin/Submissions/Index', [
            'submissions' => $submissions,
        ]);
    }

    public function verify(Request $request, Submission $submission)
    {
        if ($submission->status === 'verified') {
            return back()->with('info', 'Already verified.');
        }

        $whatsapp = new WhatsappService();
        $message = "Hi {$submission->name}, your submission has been verified. Thank you!";
        $result = $whatsapp->send($submission->phone, $message, $submission->id);

        $submission->update(['status' => 'verified']);

        if ($result['success']) {
            return back()->with('success', 'Submission verified and WhatsApp notification sent.');
        }

        return back()->with('warning', 'Submission verified but WhatsApp notification failed: ' . $result['message']);
    }
}
