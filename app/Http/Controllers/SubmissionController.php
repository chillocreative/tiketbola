<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubmissionController extends Controller
{
    public const QUOTAS = [
        'amk' => 50,
        'mbsp' => 100,
    ];

    public static function getApprovedCount(string $category): int
    {
        return Submission::where('category', $category)->where('status', 'verified')->count();
    }

    public static function getBalance(string $category): int
    {
        return max(0, self::QUOTAS[$category] - self::getApprovedCount($category));
    }

    public function create()
    {
        return Inertia::render('Submissions/Create', [
            'quotas' => [
                'amk' => ['total' => self::QUOTAS['amk'], 'balance' => self::getBalance('amk')],
                'mbsp' => ['total' => self::QUOTAS['mbsp'], 'balance' => self::getBalance('mbsp')],
            ],
        ]);
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
            'quota' => self::QUOTAS[$category],
            'balance' => self::getBalance($category),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|digits_between:1,12|unique:submissions,ic_number',
            'phone' => 'required|string|digits_between:1,11',
            'address' => 'required|string|max:1000',
            'category' => 'required|in:amk,mbsp',
        ], [
            'ic_number.unique' => 'No Kad Pengenalan ini telah didaftarkan. Setiap individu hanya dibenarkan satu pendaftaran sahaja.',
        ]);

        $validated['name'] = strtoupper($validated['name']);
        $validated['address'] = strtoupper($validated['address']);

        $submission = Submission::create($validated);

        // WhatsApp: Notify user submission received
        $whatsapp = new WhatsappService();
        $message = "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
            . "Terima kasih kerana mendaftar untuk Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
            . "Permohonan anda telah berjaya diterima dan sedang dalam proses semakan. "
            . "Sila tunggu pengesahan daripada pihak kami.\n\n"
            . "Kami akan memaklumkan status permohonan anda melalui WhatsApp.\n\n"
            . "Terima kasih.";
        $whatsapp->send($submission->phone, $message, $submission->id);

        return redirect()->route('submissions.form', $validated['category'])
            ->with('success', 'Pendaftaran berjaya dihantar!');
    }

    public function index()
    {
        $submissions = Submission::latest()->paginate(15);

        return Inertia::render('Admin/Submissions/Index', [
            'submissions' => $submissions,
            'quotas' => [
                'amk' => ['total' => self::QUOTAS['amk'], 'approved' => self::getApprovedCount('amk'), 'balance' => self::getBalance('amk')],
                'mbsp' => ['total' => self::QUOTAS['mbsp'], 'approved' => self::getApprovedCount('mbsp'), 'balance' => self::getBalance('mbsp')],
            ],
        ]);
    }

    public function verify(Request $request, Submission $submission)
    {
        if ($submission->status !== 'pending') {
            return back()->with('info', 'Permohonan ini telah diproses.');
        }

        // Check quota before approving
        if (self::getBalance($submission->category) <= 0) {
            return back()->with('warning', 'Kuota untuk kategori ini telah penuh. Tidak boleh meluluskan lagi.');
        }

        $submission->update(['status' => 'verified']);

        // WhatsApp: Notify user approved with collection details
        $whatsapp = new WhatsappService();
        $message = "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
            . "Tahniah! Permohonan tiket bola percuma anda telah *DILULUSKAN*.\n\n"
            . "Sila hadir untuk menebus dan mengambil tiket anda di:\n\n"
            . "Lokasi: *Dewan Penang 2030, Paya Keladi, Kepala Batas*\n"
            . "Tarikh: *Jumaat, 3 April 2026*\n"
            . "Masa: *8.30 malam - 10.30 malam*\n\n"
            . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
            . "Sila bawa kad pengenalan untuk pengesahan.\n\n"
            . "Jumpa anda di sana! Terima kasih.";
        $result = $whatsapp->send($submission->phone, $message, $submission->id);

        if ($result['success']) {
            return back()->with('success', 'Permohonan diluluskan dan notifikasi WhatsApp telah dihantar.');
        }

        return back()->with('warning', 'Permohonan diluluskan tetapi notifikasi WhatsApp gagal: ' . $result['message']);
    }

    public function reject(Request $request, Submission $submission)
    {
        if ($submission->status !== 'pending') {
            return back()->with('info', 'Permohonan ini telah diproses.');
        }

        $submission->update(['status' => 'rejected']);

        // WhatsApp: Notify user application rejected
        $whatsapp = new WhatsappService();
        $message = "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
            . "Terima kasih kerana mendaftar untuk Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
            . "Dukacita dimaklumkan bahawa permohonan anda *TIDAK BERJAYA* pada kali ini.\n\n"
            . "Kami menghargai minat anda dan berharap dapat melibatkan anda pada masa akan datang.\n\n"
            . "Terima kasih atas masa anda.";
        $result = $whatsapp->send($submission->phone, $message, $submission->id);

        if ($result['success']) {
            return back()->with('success', 'Permohonan ditolak dan notifikasi WhatsApp telah dihantar.');
        }

        return back()->with('warning', 'Permohonan ditolak tetapi notifikasi WhatsApp gagal: ' . $result['message']);
    }

    // Admin CRUD

    public function adminCreate()
    {
        return Inertia::render('Admin/Submissions/Create');
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|digits_between:1,12|unique:submissions,ic_number',
            'phone' => 'required|string|digits_between:1,11',
            'address' => 'required|string|max:1000',
            'category' => 'required|in:amk,mbsp',
            'status' => 'required|in:pending,verified,rejected',
        ], [
            'ic_number.unique' => 'No Kad Pengenalan ini telah didaftarkan.',
        ]);

        $validated['name'] = strtoupper($validated['name']);
        $validated['address'] = strtoupper($validated['address']);

        Submission::create($validated);

        return redirect()->route('admin.submissions')->with('success', 'Pendaftaran berjaya ditambah.');
    }

    public function edit(Submission $submission)
    {
        return Inertia::render('Admin/Submissions/Edit', [
            'submission' => $submission,
        ]);
    }

    public function update(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ic_number' => 'required|string|digits_between:1,12|unique:submissions,ic_number,' . $submission->id,
            'phone' => 'required|string|digits_between:1,11',
            'address' => 'required|string|max:1000',
            'category' => 'required|in:amk,mbsp',
            'status' => 'required|in:pending,verified,rejected',
        ], [
            'ic_number.unique' => 'No Kad Pengenalan ini telah didaftarkan.',
        ]);

        $validated['name'] = strtoupper($validated['name']);
        $validated['address'] = strtoupper($validated['address']);

        $submission->update($validated);

        return redirect()->route('admin.submissions')->with('success', 'Pendaftaran berjaya dikemaskini.');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return back()->with('success', 'Pendaftaran berjaya dipadam.');
    }
}
