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

        if (self::getBalance($category) <= 0) {
            return redirect()->route('submissions.create')
                ->with('warning', 'Maaf, kuota untuk kategori ini telah penuh.');
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
        $category = $request->input('category');
        if (isset(self::QUOTAS[$category]) && self::getBalance($category) <= 0) {
            return redirect()->route('submissions.create')
                ->with('warning', 'Maaf, kuota untuk kategori ini telah penuh.');
        }

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

        // WhatsApp: Notify user submission received (randomized message)
        $whatsapp = new WhatsappService();
        $messages = [
            "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
                . "Terima kasih kerana mendaftar untuk Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
                . "Permohonan anda telah berjaya diterima dan sedang dalam proses semakan. "
                . "Sila tunggu pengesahan daripada pihak kami.\n\n"
                . "Kami akan memaklumkan status permohonan anda melalui WhatsApp.\n\n"
                . "Terima kasih.",

            "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
                . "Terima kasih atas pendaftaran anda untuk Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
                . "Permohonan anda telah kami terima dengan jayanya dan kini dalam proses semakan. "
                . "Mohon bersabar sementara kami memproses permohonan anda.\n\n"
                . "Status permohonan anda akan dimaklumkan melalui WhatsApp.\n\n"
                . "Terima kasih atas sokongan anda.",

            "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
                . "Kami ingin mengucapkan terima kasih kerana mendaftar untuk Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
                . "Permohonan anda telah diterima dan sedang disemak oleh pihak kami. "
                . "Sila tunggu sebentar untuk pengesahan.\n\n"
                . "Anda akan menerima makluman status melalui WhatsApp.\n\n"
                . "Terima kasih kerana menyokong pasukan kita.",

            "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
                . "Pendaftaran anda untuk Tiket Bola Percuma Pulau Pinang vs Sabah telah berjaya diterima!\n\n"
                . "Permohonan anda kini sedang dalam proses semakan. "
                . "Kami akan menghubungi anda melalui WhatsApp setelah semakan selesai.\n\n"
                . "Terima kasih kerana sudi mendaftar.",

            "Assalamualaikum dan salam sejahtera {$submission->name},\n\n"
                . "Terima kasih kerana berminat dengan Tiket Bola Percuma Pulau Pinang vs Sabah.\n\n"
                . "Permohonan anda telah kami terima dan akan disemak secepat mungkin. "
                . "Pengesahan akan dimaklumkan kepada anda melalui WhatsApp.\n\n"
                . "Terima kasih dan salam hormat.",
        ];
        $message = $messages[array_rand($messages)];
        $whatsapp->send($submission->phone, $message, $submission->id);

        return redirect()->route('submissions.form', $validated['category'])
            ->with('success', 'Pendaftaran berjaya dihantar!');
    }

    public function index(Request $request)
    {
        $query = Submission::latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Submissions/Index', [
            'submissions' => $submissions,
            'filters' => $request->only('search', 'status'),
            'quotas' => [
                'amk' => ['total' => self::QUOTAS['amk'], 'approved' => self::getApprovedCount('amk'), 'balance' => self::getBalance('amk')],
                'mbsp' => ['total' => self::QUOTAS['mbsp'], 'approved' => self::getApprovedCount('mbsp'), 'balance' => self::getBalance('mbsp')],
            ],
        ]);
    }

    public function issue(Submission $submission)
    {
        if ($submission->status !== 'verified') {
            return redirect()->route('admin.submissions')->with('warning', 'Hanya permohonan yang diluluskan boleh diserahkan tiket.');
        }

        $submission->status = 'issued';
        $submission->save();

        return redirect()->route('admin.submissions')->with('success', 'Tiket telah dikeluarkan untuk ' . $submission->name . '.');
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

        // WhatsApp: Notify user approved with collection details (randomized message)
        $whatsapp = new WhatsappService();
        $name = $submission->name;
        $messages = [
            // Variation 1
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Tahniah! Permohonan anda untuk mendapatkan tajaan tiket percuma telah berjaya.\n\n"
                . "PERHATIAN: Sila pastikan anda telah like, comment dan share posting seperti yang ditetapkan. "
                . "Anda juga dikehendaki menunjukkan bukti kepada urusetia semasa menuntut tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Pihak urusetia berhak untuk menolak tuntutan sekiranya anda gagal mengemukakan bukti tersebut.\n\n"
                . "Sila hadir untuk menebus dan mengambil tiket anda di:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Sila bawa kad pengenalan untuk pengesahan. Wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Sekiranya tiada tuntutan tiket dibuat sehingga hari Sabtu seperti yang ditetapkan, "
                . "permohonan anda akan terbatal secara automatik.\n\n"
                . "Sebarang masalah berkaitan tuntutan tiket hendaklah dimaklumkan kepada urusetia "
                . "sebelum jam 12.00 tengah hari pada hari Sabtu.\n\n"
                . "Jumpa anda di sana! Terima kasih.",

            // Variation 2
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Alhamdulillah, permohonan anda untuk tajaan tiket percuma telah diluluskan!\n\n"
                . "PENTING: Pastikan anda telah like, comment dan share posting berikut. "
                . "Bukti perlu ditunjukkan kepada urusetia ketika menuntut tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Urusetia berhak menolak tuntutan jika bukti tidak dapat dikemukakan.\n\n"
                . "Sila hadir untuk mengambil tiket anda di lokasi berikut:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Bawa kad pengenalan asal untuk tujuan pengesahan. Wakil tidak dibenarkan sama sekali.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Tuntutan yang tidak dibuat sehingga hari Sabtu akan terbatal secara automatik.\n\n"
                . "Jika terdapat sebarang masalah, sila hubungi urusetia sebelum jam 12.00 tengah hari pada hari Sabtu.\n\n"
                . "Kami menanti kehadiran anda! Terima kasih.",

            // Variation 3
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Tahniah dan syabas! Permohonan tiket percuma anda telah berjaya diluluskan.\n\n"
                . "PERINGATAN: Anda wajib like, comment dan share posting di pautan berikut. "
                . "Sila sediakan bukti untuk ditunjukkan semasa pengambilan tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Tuntutan boleh ditolak oleh urusetia sekiranya bukti tidak dikemukakan.\n\n"
                . "Tiket boleh diambil di:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Pastikan anda membawa kad pengenalan. Pengambilan oleh wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Permohonan akan terbatal automatik jika tiket tidak dituntut sebelum tamat hari Sabtu.\n\n"
                . "Hubungi urusetia sebelum jam 12.00 tengah hari Sabtu jika ada sebarang isu.\n\n"
                . "Jumpa di sana! Terima kasih atas sokongan anda.",

            // Variation 4
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Berita baik! Permohonan tajaan tiket percuma anda telah berjaya.\n\n"
                . "PERHATIAN: Sebelum menuntut tiket, sila pastikan anda sudah like, comment dan share posting ini. "
                . "Urusetia akan meminta bukti semasa pengambilan tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Pihak urusetia berhak menolak tuntutan tanpa bukti yang sah.\n\n"
                . "Sila datang untuk menebus tiket anda di:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Kad pengenalan wajib dibawa untuk pengesahan identiti. Wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Sekiranya tiket tidak dituntut sehingga hari Sabtu, permohonan anda akan dibatalkan secara automatik.\n\n"
                . "Sebarang masalah hendaklah dilaporkan kepada urusetia sebelum jam 12.00 tengah hari Sabtu.\n\n"
                . "Jangan lepaskan peluang ini! Terima kasih.",

            // Variation 5
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Kami gembira memaklumkan bahawa permohonan tiket percuma anda telah diluluskan!\n\n"
                . "PENTING: Anda perlu like, comment dan share posting yang ditetapkan. "
                . "Bukti hendaklah ditunjukkan kepada urusetia semasa tuntutan tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Tanpa bukti yang sah, urusetia berhak untuk menolak tuntutan anda.\n\n"
                . "Pengambilan tiket boleh dibuat di:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Sila bawa kad pengenalan untuk tujuan pengesahan. Tiada wakil dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Kegagalan menuntut tiket sebelum tamat hari Sabtu akan menyebabkan permohonan anda terbatal automatik.\n\n"
                . "Sila maklumkan urusetia sebelum jam 12.00 tengah hari Sabtu jika terdapat sebarang masalah.\n\n"
                . "Terima kasih dan jumpa di sana!",

            // Variation 6
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Tahniah! Anda telah terpilih untuk menerima tajaan tiket percuma.\n\n"
                . "PERHATIAN: Anda dikehendaki like, comment dan share posting di pautan di bawah. "
                . "Bukti mesti dikemukakan kepada urusetia ketika mengambil tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Urusetia berhak untuk menolak tuntutan sekiranya bukti tidak dapat ditunjukkan.\n\n"
                . "Sila ambil tiket anda di lokasi berikut:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Wajib bawa kad pengenalan asal. Pengambilan melalui wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Tiket yang tidak dituntut sehingga Sabtu akan terbatal secara automatik.\n\n"
                . "Jika ada masalah, maklumkan urusetia sebelum jam 12.00 tengah hari pada hari Sabtu.\n\n"
                . "Kami tunggu kehadiran anda! Terima kasih.",

            // Variation 7
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Syabas dan tahniah! Permohonan anda untuk tiket percuma telah berjaya diluluskan.\n\n"
                . "SYARAT PENTING: Like, comment dan share posting berikut sebelum menuntut tiket. "
                . "Anda wajib menunjukkan bukti kepada urusetia.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Tuntutan tanpa bukti boleh ditolak oleh pihak urusetia.\n\n"
                . "Hadir untuk mengambil tiket di:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Bawa kad pengenalan untuk pengesahan. Wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Permohonan terbatal automatik jika tiket tidak dituntut selewat-lewatnya hari Sabtu.\n\n"
                . "Sebarang isu berkaitan tuntutan perlu dimaklumkan sebelum jam 12.00 tengah hari Sabtu.\n\n"
                . "Sampai jumpa di sana! Terima kasih.",

            // Variation 8
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Permohonan tajaan tiket percuma anda telah diluluskan. Tahniah!\n\n"
                . "PERHATIAN: Sila like, comment dan share posting di bawah ini terlebih dahulu. "
                . "Urusetia akan memeriksa bukti semasa anda menuntut tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Pihak urusetia berhak menolak tuntutan jika bukti tidak dikemukakan.\n\n"
                . "Berikut adalah maklumat pengambilan tiket:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Kad pengenalan asal perlu dibawa. Pengambilan oleh wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Sekiranya tiket tidak dituntut menjelang hari Sabtu, permohonan anda akan terbatal secara automatik.\n\n"
                . "Sebarang masalah sila hubungi urusetia sebelum jam 12.00 tengah hari Sabtu.\n\n"
                . "Terima kasih dan jangan lupa hadir!",

            // Variation 9
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Alhamdulillah, tahniah! Anda layak menerima tajaan tiket percuma.\n\n"
                . "PERINGATAN: Pastikan anda sudah like, comment dan share posting yang dinyatakan di bawah. "
                . "Sediakan bukti untuk ditunjukkan semasa pengambilan tiket.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Sekiranya bukti tidak dapat dikemukakan, urusetia berhak menolak tuntutan anda.\n\n"
                . "Sila hadir di lokasi berikut untuk mengambil tiket:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Sila bawa kad pengenalan asal anda. Wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Jika tiket tidak dituntut sehingga hari Sabtu yang ditetapkan, permohonan anda akan terbatal automatik.\n\n"
                . "Maklumkan urusetia sebelum jam 12.00 tengah hari Sabtu sekiranya terdapat masalah.\n\n"
                . "Jumpa anda di sana! Terima kasih.",

            // Variation 10
            "Assalamualaikum dan salam sejahtera {$name},\n\n"
                . "Tahniah diucapkan! Permohonan anda untuk tajaan tiket percuma telah berjaya.\n\n"
                . "PENTING: Anda perlu like, comment dan share posting yang berikut sebelum hadir mengambil tiket. "
                . "Tunjukkan bukti kepada urusetia semasa tuntutan dibuat.\n\n"
                . "Facebook : https://www.facebook.com/share/p/189CdeYR8Y/\n\n"
                . "Tiktok : https://vt.tiktok.com/ZSHh5GyUA/\n\n"
                . "Pihak urusetia berhak untuk menolak tuntutan tanpa bukti.\n\n"
                . "Maklumat pengambilan tiket adalah seperti berikut:\n\n"
                . "*Lokasi:*\nDewan Penang 2030, Paya Keladi, Kepala Batas\n\n"
                . "*Tarikh / Masa:*\n\n"
                . "*Jumaat, 3 April 2026 (8.30 malam - 10.30 malam)*\n\n"
                . "*Sabtu, 4 April 2026 (10.00 pagi - 12.00 tengah hari)*\n\n"
                . "Google Maps: https://maps.app.goo.gl/U4c5GUAbxWbJaryb7\n\n"
                . "Sila bawa kad pengenalan untuk pengesahan. Wakil tidak dibenarkan.\n\n"
                . "*PERINGATAN TUNTUTAN TIKET*\n\n"
                . "Permohonan akan terbatal secara automatik sekiranya tiket tidak dituntut sehingga hari Sabtu.\n\n"
                . "Hubungi urusetia sebelum jam 12.00 tengah hari Sabtu jika ada sebarang masalah.\n\n"
                . "Kami nantikan kehadiran anda! Terima kasih.",
        ];
        $message = $messages[array_rand($messages)];
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

        // WhatsApp: Notify user application rejected (randomized message)
        $whatsapp = new WhatsappService();
        $name = $submission->name;
        $messages = [
            // Variation 1
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih atas permohonan anda. Dukacita dimaklumkan bahawa permohonan anda untuk mendapatkan tajaan tiket percuma adalah *TIDAK BERJAYA*.\n\n"
                . "Permohonan adalah terhad dan diproses berdasarkan kelayakan serta kekosongan yang ada.\n\n"
                . "Terima kasih atas minat dan sokongan anda.",

            // Variation 2
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Kami menghargai permohonan anda. Namun, dukacita dimaklumkan bahawa permohonan tajaan tiket percuma anda adalah *TIDAK BERJAYA*.\n\n"
                . "Jumlah permohonan yang diterima melebihi kuota yang tersedia dan pemilihan dibuat berdasarkan kelayakan.\n\n"
                . "Terima kasih kerana sudi memohon dan menyokong.",

            // Variation 3
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih kerana memohon tajaan tiket percuma. Dengan penuh hormat, kami ingin memaklumkan bahawa permohonan anda *TIDAK BERJAYA*.\n\n"
                . "Permohonan telah diproses berdasarkan kelayakan dan kekosongan yang terhad.\n\n"
                . "Kami menghargai minat dan sokongan anda. Terima kasih.",

            // Variation 4
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih atas minat anda terhadap tajaan tiket percuma. Dukacita dimaklumkan bahawa permohonan anda kali ini adalah *TIDAK BERJAYA*.\n\n"
                . "Keputusan dibuat berdasarkan kelayakan dan had kuota yang ditetapkan.\n\n"
                . "Terima kasih atas sokongan dan minat yang ditunjukkan.",

            // Variation 5
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Kami ingin mengucapkan terima kasih atas permohonan anda. Walau bagaimanapun, permohonan tajaan tiket percuma anda adalah *TIDAK BERJAYA*.\n\n"
                . "Permohonan yang diterima melebihi kekosongan yang ada dan diproses mengikut kelayakan.\n\n"
                . "Terima kasih kerana menyokong. Semoga berjaya pada masa hadapan.",

            // Variation 6
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih kerana berminat dengan tajaan tiket percuma. Kami kesal memaklumkan bahawa permohonan anda adalah *TIDAK BERJAYA*.\n\n"
                . "Pemilihan dibuat berdasarkan kelayakan dan kuota yang terhad.\n\n"
                . "Sokongan anda amat dihargai. Terima kasih.",

            // Variation 7
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Kami menghargai permohonan yang telah dikemukakan. Namun begitu, permohonan anda untuk tajaan tiket percuma adalah *TIDAK BERJAYA*.\n\n"
                . "Bilangan permohonan adalah melebihi kuota dan kelulusan tertakluk kepada kelayakan serta kekosongan.\n\n"
                . "Terima kasih atas minat dan sokongan anda yang berterusan.",

            // Variation 8
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih atas permohonan tajaan tiket percuma anda. Dengan hormatnya, kami memaklumkan bahawa permohonan anda *TIDAK BERJAYA* pada kali ini.\n\n"
                . "Permohonan diproses mengikut keutamaan, kelayakan dan had kekosongan yang ada.\n\n"
                . "Kami mengucapkan terima kasih atas sokongan anda.",

            // Variation 9
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Terima kasih kerana sudi memohon tajaan tiket percuma. Dukacita dimaklumkan bahawa permohonan anda adalah *TIDAK BERJAYA*.\n\n"
                . "Kuota adalah terhad dan permohonan telah dinilai berdasarkan kelayakan yang ditetapkan.\n\n"
                . "Terima kasih atas minat dan semangat sokongan anda.",

            // Variation 10
            "Assalamualaikum dan salam sejahtera {$name}.\n\n"
                . "Permohonan anda untuk tajaan tiket percuma telah disemak. Kami kesal memaklumkan bahawa permohonan anda adalah *TIDAK BERJAYA*.\n\n"
                . "Pemilihan dibuat berdasarkan kelayakan dan kekosongan yang terhad.\n\n"
                . "Sokongan dan minat anda amat kami hargai. Terima kasih.",
        ];
        $message = $messages[array_rand($messages)];
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
            'status' => 'required|in:pending,verified,rejected,issued',
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
            'status' => 'required|in:pending,verified,rejected,issued',
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

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:submissions,id',
        ]);

        Submission::whereIn('id', $validated['ids'])->delete();

        return back()->with('success', count($validated['ids']) . ' pendaftaran berjaya dipadam.');
    }

    public function export()
    {
        $submissions = Submission::latest()->get();

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
        $csv .= "Nama,No KP,Telefon,Alamat,Kategori,Status,Tarikh\n";

        foreach ($submissions as $s) {
            $csv .= '"' . str_replace('"', '""', $s->name) . '",';
            $csv .= $s->ic_number . ',';
            $csv .= $s->phone . ',';
            $csv .= '"' . str_replace('"', '""', $s->address) . '",';
            $csv .= ($s->category === 'mbsp' ? 'MBSP' : 'AMK') . ',';
            $csv .= (['verified' => 'Diluluskan', 'rejected' => 'Ditolak', 'issued' => 'Tiket Telah Diambil', 'pending' => 'Menunggu'][$s->status] ?? $s->status) . ',';
            $csv .= $s->created_at->format('d/m/Y') . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="pendaftaran_' . date('Ymd') . '.csv"',
        ]);
    }
}
