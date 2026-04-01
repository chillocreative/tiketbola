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
            ->with('success', 'Pendaftaran berjaya dihantar!');
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
            return back()->with('info', 'Sudah disahkan.');
        }

        $whatsapp = new WhatsappService();
        $message = "Assalamualaikum {$submission->name}, pendaftaran tiket bola anda telah disahkan. Jumpa anda di Stadium Bandaraya, Pulau Pinang! Terima kasih.";
        $result = $whatsapp->send($submission->phone, $message, $submission->id);

        $submission->update(['status' => 'verified']);

        if ($result['success']) {
            return back()->with('success', 'Pendaftaran disahkan dan notifikasi WhatsApp telah dihantar.');
        }

        return back()->with('warning', 'Pendaftaran disahkan tetapi notifikasi WhatsApp gagal: ' . $result['message']);
    }
}
