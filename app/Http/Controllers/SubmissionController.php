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

    public function form(string $category)
    {
        $categories = [
            'amk' => 'Tajaan Angkatan Muda Keadilan Cabang Kepala Batas & JBPP Pinang Tunggal',
            'mbsp' => 'Tajaan Ahli Majlis MBSP, Pegawai Penyelaras KADUN Pinang Tunggal, Parti KEADILAN Cabang Kepala Batas',
        ];

        if (!isset($categories[$category])) {
            abort(404);
        }

        return Inertia::render('Submissions/Form', [
            'category' => $category,
            'categoryLabel' => $categories[$category],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|digits_between:1,12',
            'phone' => 'required|string|digits_between:1,11',
            'address' => 'required|string|max:1000',
            'category' => 'required|in:amk,mbsp',
        ]);

        $validated['name'] = strtoupper($validated['name']);
        $validated['address'] = strtoupper($validated['address']);

        Submission::create($validated);

        return redirect()->route('submissions.form', $validated['category'])
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
