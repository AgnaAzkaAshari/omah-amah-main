<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class OmahAmahController extends Controller
{
    // =========================================================
    // 1. API KAMAR
    // =========================================================

    public function rooms()
    {
        $rooms = DB::table('rooms')
            ->where('is_active', true)
            ->orderBy('room_code')
            ->get();

        foreach ($rooms as $room) {
            $room->facilities = DB::table('facilities')
                ->join('room_facility', 'facilities.id', '=', 'room_facility.facility_id')
                ->where('room_facility.room_id', $room->id)
                ->select('facilities.id', 'facilities.name')
                ->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kamar berhasil ditampilkan',
            'data' => $rooms
        ]);
    }

    public function roomDetail($slug)
    {
        $room = DB::table('rooms')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }

        $room->facilities = DB::table('facilities')
            ->join('room_facility', 'facilities.id', '=', 'room_facility.facility_id')
            ->where('room_facility.room_id', $room->id)
            ->select('facilities.id', 'facilities.name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $room
        ]);
    }

    public function storeRoom(Request $request)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:20|unique:rooms,room_code',
            'name' => 'required|string|max:100',
            'room_type' => 'required|string|max:40',
            'monthly_price' => 'nullable|numeric|min:0',
            'availability' => 'required|in:tersedia,penuh,perlu_konfirmasi',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (DB::table('rooms')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $imagePath = null;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('rooms', 'public');
            $imagePath = asset('storage/' . $path);
        }

        $id = DB::table('rooms')->insertGetId([
            'room_code' => $validated['room_code'],
            'name' => $validated['name'],
            'slug' => $slug,
            'room_type' => $validated['room_type'],
            'monthly_price' => $validated['monthly_price'],
            'availability' => $validated['availability'],
            'image_url' => $imagePath,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->has('facilities') && is_array($request->facilities)) {
            foreach ($request->facilities as $facilityId) {
                DB::table('room_facility')->insert([
                    'room_id' => $id,
                    'facility_id' => $facilityId,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kamar berhasil disimpan.',
            'data' => ['id' => $id]
        ], 201);
    }

    public function showRoom($id)
    {
        $room = DB::table('rooms')->where('id', $id)->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }

        $room->facilities = DB::table('facilities')
            ->join('room_facility', 'facilities.id', '=', 'room_facility.facility_id')
            ->where('room_facility.room_id', $room->id)
            ->select('facilities.id', 'facilities.name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $room
        ]);
    }

    public function updateRoom(Request $request, $id)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:20|unique:rooms,room_code,' . $id,
            'name' => 'required|string|max:100',
            'room_type' => 'required|string|max:40',
            'monthly_price' => 'nullable|numeric|min:0',
            'availability' => 'required|in:tersedia,penuh,perlu_konfirmasi',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $room = DB::table('rooms')->where('id', $id)->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan'
            ], 404);
        }

        $imagePath = $room->image_url;

        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('rooms', 'public');
            $imagePath = asset('storage/' . $path);
        }

        DB::table('rooms')
            ->where('id', $id)
            ->update([
                'room_code' => $validated['room_code'],
                'name' => $validated['name'],
                'room_type' => $validated['room_type'],
                'monthly_price' => $validated['monthly_price'],
                'availability' => $validated['availability'],
                'image_url' => $imagePath,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Data kamar berhasil diperbarui.'
        ]);
    }

    public function destroyRoom($id)
    {
        DB::table('room_facility')->where('room_id', $id)->delete();

        $deleted = DB::table('rooms')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Kamar telah dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus kamar.'
        ], 500);
    }

    // =========================================================
    // 2. API FASILITAS
    // =========================================================

    public function facilities()
    {
        $data = DB::table('facilities')
            ->where('is_active', true)
            ->orderBy('category')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function storeFacility(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name',
            'category' => 'required|in:kamar,bersama,layanan',
            'description' => 'nullable|string',
        ]);

        $id = DB::table('facilities')->insertGetId([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil disimpan.',
            'data' => ['id' => $id]
        ], 201);
    }

    public function updateFacility(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name,' . $id,
            'category' => 'required|in:kamar,bersama,layanan',
            'description' => 'nullable|string',
        ]);

        DB::table('facilities')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'category' => $validated['category'],
                'description' => $validated['description'] ?? null,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Data fasilitas berhasil diperbarui.'
        ]);
    }

    public function destroyFacility($id)
    {
        DB::table('room_facility')->where('facility_id', $id)->delete();

        $deleted = DB::table('facilities')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Fasilitas berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus fasilitas.'
        ], 500);
    }

    // =========================================================
    // 3. API INFORMASI KOST DAN FAQ
    // =========================================================

    public function profile()
    {
        $data = DB::table('kost_profiles')->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:30',
            'visit_hours' => 'nullable|string|max:50',
            'quiet_hours' => 'nullable|string|max:50',
            'maps_link' => 'nullable|string',
        ]);

        DB::table('kost_profiles')
            ->where('id', 1)
            ->update([
                'name' => $validated['name'],
                'address' => $validated['address'] ?? null,
                'description' => $validated['description'] ?? null,
                'contact_name' => $validated['contact_name'] ?? null,
                'contact_phone' => $validated['contact_phone'] ?? null,
                'visit_hours' => $validated['visit_hours'] ?? null,
                'quiet_hours' => $validated['quiet_hours'] ?? null,
                'maps_link' => $validated['maps_link'] ?? null,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Informasi kost berhasil diperbarui.'
        ]);
    }

    public function faqs()
    {
        $data = DB::table('faqs')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // =========================================================
    // 4. API PESAN DAN PERTANYAAN
    // =========================================================

    public function storeInquiry(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:120',
            'category' => 'required|in:kamar,harga,fasilitas,laundry,lainnya',
            'question' => 'required|string',
        ]);

        $id = DB::table('inquiries')->insertGetId([
            'sender_name' => $validated['sender_name'],
            'category' => $validated['category'],
            'source' => 'website',
            'question' => $validated['question'],
            'status' => 'baru',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dikirim.',
            'data' => ['id' => $id]
        ], 201);
    }

    public function getInquiries()
    {
        $inquiries = DB::table('inquiries')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $inquiries
        ]);
    }

    public function updateInquiryStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:baru,diproses,selesai',
            'answer' => 'nullable|string'
        ]);

        DB::table('inquiries')
            ->where('id', $id)
            ->update([
                'status' => $validated['status'],
                'answer' => $validated['answer'] ?? null,
                'updated_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Status pertanyaan telah diperbarui.'
        ]);
    }

    // =========================================================
    // 5. API LAUNDRY
    // =========================================================

    public function getLaundry()
    {
        $data = DB::table('laundry_services')
            ->where('id', 1)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateLaundry(Request $request, $id)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:120',
            'price_per_kg' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
            'minimum_weight' => 'nullable|numeric|min:0.1',
            'is_active' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        DB::table('laundry_services')
            ->where('id', $id)
            ->update([
                'service_name' => $validated['service_name'],
                'price_per_kg' => $validated['price_per_kg'],
                'estimated_days' => $validated['estimated_days'],
                'minimum_weight' => $validated['minimum_weight'] ?? 3.0,
                'is_active' => $validated['is_active'],
                'notes' => $validated['notes'] ?? null,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Informasi laundry berhasil diperbarui.'
        ]);
    }

    // =========================================================
    // 6. API CHATBOT
    // =========================================================

    public function askChatbot(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'sender_name' => 'nullable|string|max:120'
        ]);

        $message = strtolower(trim($validated['message']));
        $reply = '';
        $category = 'lainnya';

        // 1. LAUNDRY
        // Harus diperiksa sebelum harga kamar.
        if (
            str_contains($message, 'laundry') ||
            str_contains($message, 'cuci baju') ||
            str_contains($message, 'cuci pakaian') ||
            str_contains($message, 'cuci')
        ) {
            $laundry = DB::table('laundry_services')
                ->where('is_active', true)
                ->orderBy('id')
                ->first();

            if ($laundry && $laundry->price_per_kg !== null) {
                $harga = number_format(
                    $laundry->price_per_kg,
                    0,
                    ',',
                    '.'
                );

                $reply = "Layanan laundry Omah Amah tersedia dengan tarif Rp{$harga} per kg.";

                if ($laundry->estimated_days) {
                    $reply .= " Estimasi pengerjaan sekitar {$laundry->estimated_days} hari.";
                }

                if ($laundry->minimum_weight) {
                    $reply .= " Minimal berat cucian {$laundry->minimum_weight} kg.";
                }

                if (!empty($laundry->notes)) {
                    $reply .= " " . $laundry->notes;
                }
            } else {
                $reply = "Informasi harga laundry belum tersedia. Silakan hubungi pengelola Omah Amah.";
            }

            $category = 'laundry';
        }

        // 2. HARGA KAMAR
        elseif (
            str_contains($message, 'harga') ||
            str_contains($message, 'biaya') ||
            str_contains($message, 'tarif') ||
            str_contains($message, 'sewa') ||
            str_contains($message, 'bayar')
        ) {
            if (
                str_contains($message, 'non ac') ||
                str_contains($message, 'non-ac') ||
                str_contains($message, 'tanpa ac')
            ) {
                $reply = "Harga kamar non-AC di Omah Amah berkisar Rp900.000 sampai Rp1.600.000 per bulan.";

            } elseif (
                str_contains($message, 'kamar mandi bersama') ||
                str_contains($message, 'kamar mandi luar')
            ) {
                $reply = "Harga kamar dengan kamar mandi bersama berkisar Rp900.000 sampai Rp1.200.000 per bulan.";

            } elseif (
                str_contains($message, 'kamar mandi dalam') ||
                str_contains($message, 'kamar mandi privat')
            ) {
                $reply = "Harga kamar dengan kamar mandi dalam berkisar Rp1.300.000 sampai Rp2.000.000 per bulan.";

            } elseif (preg_match('/\bac\b/i', $message)) {
                $reply = "Harga kamar dengan AC di Omah Amah berkisar Rp1.600.000 sampai Rp2.000.000 per bulan.";

            } else {
                $reply = "Berikut daftar harga kamar Omah Amah:\n\n"
                    . "1. Non-AC: Rp900.000 - Rp1.600.000 per bulan.\n"
                    . "2. AC: Rp1.600.000 - Rp2.000.000 per bulan.\n"
                    . "3. Kamar mandi bersama: Rp900.000 - Rp1.200.000 per bulan.\n"
                    . "4. Kamar mandi dalam: Rp1.300.000 - Rp2.000.000 per bulan.\n\n"
                    . "Harga menyesuaikan tipe kamar dan fasilitas.";
            }

            $category = 'harga';
        }

        // 3. JUMLAH KAMAR
        elseif (
            str_contains($message, 'jumlah kamar') ||
            str_contains($message, 'total kamar') ||
            str_contains($message, 'berapa kamar')
        ) {
            $jumlah = DB::table('rooms')
                ->where('is_active', true)
                ->count();

            $reply = "Saat ini terdapat {$jumlah} data kamar aktif yang tercatat di database Omah Amah.";

            $category = 'kamar';
        }

        // 4. KETERSEDIAAN KAMAR
        elseif (
            str_contains($message, 'kamar kosong') ||
            str_contains($message, 'kamar tersedia') ||
            str_contains($message, 'ketersediaan kamar') ||
            str_contains($message, 'ada kamar') ||
            str_contains($message, 'masih ada kamar') ||
            str_contains($message, 'kamar yang kosong')
        ) {
            $tersedia = DB::table('rooms')
                ->where('is_active', true)
                ->where('availability', 'tersedia')
                ->orderBy('room_code')
                ->get();

            $konfirmasi = DB::table('rooms')
                ->where('is_active', true)
                ->where('availability', 'perlu_konfirmasi')
                ->count();

            if ($tersedia->isEmpty()) {
                $reply = "Saat ini belum ada kamar yang tercatat tersedia di database Omah Amah.";
            } else {
                $reply = "Berikut kamar yang tercatat tersedia:\n\n";

                foreach ($tersedia as $kamar) {
                    $reply .= "- {$kamar->room_code} - {$kamar->name}\n";
                }

                $reply .= "\nTotal kamar tersedia: {$tersedia->count()} kamar.";

                if ($konfirmasi > 0) {
                    $reply .= "\nAda {$konfirmasi} kamar yang masih perlu dikonfirmasi kepada pengelola.";
                }
            }

            $category = 'kamar';
        }

        // 5. LOKASI
        elseif (
            str_contains($message, 'alamat') ||
            str_contains($message, 'lokasi') ||
            str_contains($message, 'dimana') ||
            str_contains($message, 'di mana')
        ) {
            $profile = DB::table('kost_profiles')->first();

            $alamat = $profile->address ?? 'Alamat belum tersedia';

            $reply = "Omah Amah beralamat di {$alamat}.";

            if ($profile && !empty($profile->maps_link)) {
                $reply .= "\nGoogle Maps: " . $profile->maps_link;
            }

            $category = 'lainnya';
        }

        // 6. KAMAR MANDI
        elseif (
            str_contains($message, 'kamar mandi') ||
            str_contains($message, 'toilet')
        ) {
            $reply = "Omah Amah menyediakan pilihan kamar dengan kamar mandi bersama dan kamar mandi dalam. Silakan hubungi pengelola untuk memastikan ketersediaan tipe kamar.";

            $category = 'fasilitas';
        }

        // 7. KULKAS
        elseif (str_contains($message, 'kulkas')) {
            $reply = "Omah Amah menyediakan kulkas bersama yang dapat digunakan oleh penghuni.";

            $category = 'fasilitas';
        }

        // 8. DAPUR
        elseif (str_contains($message, 'dapur')) {
            $reply = "Omah Amah menyediakan dapur bersama yang dapat digunakan oleh penghuni.";

            $category = 'fasilitas';
        }

        // 9. FASILITAS UMUM
        elseif (
            str_contains($message, 'fasilitas') ||
            str_contains($message, 'wifi') ||
            str_contains($message, 'lemari') ||
            str_contains($message, 'meja') ||
            str_contains($message, 'kasur')
        ) {
            $fasilitas = DB::table('facilities')
                ->where('is_active', true)
                ->orderBy('category')
                ->get();

            if ($fasilitas->isEmpty()) {
                $reply = "Informasi fasilitas belum tersedia di database.";
            } else {
                $reply = "Berikut fasilitas yang tercatat di database Omah Amah:\n\n";

                foreach ($fasilitas as $item) {
                    $reply .= "- {$item->name}\n";
                }

                $reply .= "\nSelain itu, tersedia kulkas dan dapur bersama untuk penghuni.";
            }

            $category = 'fasilitas';
        }

        // 10. SAPAAN
        elseif (
            str_contains($message, 'halo') ||
            str_contains($message, 'hai') ||
            str_contains($message, 'selamat pagi') ||
            str_contains($message, 'selamat siang') ||
            str_contains($message, 'selamat sore') ||
            str_contains($message, 'selamat malam')
        ) {
            $reply = "Halo! Saya Amah AI, asisten virtual Omah Amah. Ada yang bisa saya bantu mengenai kamar, harga, fasilitas, lokasi, atau laundry?";
        }

        // 11. JAWABAN DEFAULT
        else {
            $reply = "Mohon maaf, saya belum memahami pertanyaan tersebut. Silakan tanyakan tentang harga kamar, kamar kosong, fasilitas, lokasi, atau laundry Omah Amah.";
        }

        // SIMPAN RIWAYAT CHAT
        DB::table('inquiries')->insert([
            'sender_name' => $validated['sender_name'] ?? 'Anonim',
            'category' => $category,
            'source' => 'chatbot_ai',
            'question' => $validated['message'],
            'answer' => $reply,
            'status' => 'selesai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'reply' => $reply
        ]);
    }

    // =========================================================
    // 7. PENGATURAN ADMIN
    // =========================================================

    public function getAdminProfile()
    {
        $data = DB::table('admin_profiles')
            ->where('id', 1)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateAdminProfile(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:120',
            'email' => 'required|email',
            'telepon' => 'nullable|string|max:30',
            'peran' => 'required|string',
            'foto_file' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $profile = DB::table('admin_profiles')
            ->where('id', 1)
            ->first();

        $fotoPath = $profile ? $profile->foto_url : null;

        if ($request->hasFile('foto_file')) {
            $path = $request->file('foto_file')->store('profiles', 'public');
            $fotoPath = asset('storage/' . $path);
        }

        $exists = DB::table('admin_profiles')
            ->where('id', 1)
            ->exists();

        $data = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'telepon' => $validated['telepon'] ?? null,
            'peran' => $validated['peran'],
            'foto_url' => $fotoPath,
            'updated_at' => now()
        ];

        if ($exists) {
            DB::table('admin_profiles')->where('id', 1)->update($data);
        } else {
            $data['id'] = 1;
            $data['created_at'] = now();

            DB::table('admin_profiles')->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'foto_url' => $fotoPath
        ]);
    }

    public function getNotificationSettings()
    {
        $data = DB::table('notification_settings')
            ->where('id', 1)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function updateNotificationSettings(Request $request)
    {
        $exists = DB::table('notification_settings')
            ->where('id', 1)
            ->exists();

        $data = [
            'notif_penghuni' => $request->input('penghuni_baru', false),
            'notif_pembayaran' => $request->input('pembayaran', false),
            'notif_jatuh_tempo' => $request->input('jatuh_tempo', false),
            'notif_pengumuman' => $request->input('pengumuman', false),
            'updated_at' => now()
        ];

        if ($exists) {
            DB::table('notification_settings')->where('id', 1)->update($data);
        } else {
            $data['id'] = 1;
            $data['created_at'] = now();

            DB::table('notification_settings')->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan notifikasi disimpan.'
        ]);
    }

    // =========================================================
    // 8. LOGIN ADMIN DAN PENGHUNI
    // =========================================================

    public function loginAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|string|in:admin,penghuni'
        ]);

        $user = DB::table('users')
            ->where('email', $validated['email'])
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email belum terdaftar.'
            ], 401);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi salah.'
            ], 401);
        }

        $dbRole = $user->role ?? 'penghuni';
        $isAdmin = in_array($dbRole, ['admin', 'super_admin']);

        if ($validated['role'] === 'admin' && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Akun ini bukan admin.'
            ], 403);
        }

        if ($validated['role'] === 'penghuni' && $isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login menggunakan jalur admin.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $dbRole
            ]
        ]);
    }

    // =========================================================
    // 9. REGISTER PENGHUNI
    // =========================================================

    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $id = DB::table('users')->insertGetId([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'penghuni',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dibuat. Silakan masuk.',
            'data' => ['id' => $id]
        ], 201);
    }

    // =========================================================
    // 10. LUPA PASSWORD
    // =========================================================

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $status = Password::broker('users')->sendResetLink([
                'email' => $validated['email'],
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tautan reset password berhasil dikirim. Silakan periksa email Anda.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => __($status)
            ], 400);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Email reset gagal dikirim. Periksa konfigurasi email.'
            ], 500);
        }
    }

    // =========================================================
    // 11. RESET PASSWORD
    // =========================================================

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $status = Password::broker('users')->reset(
                [
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'password_confirmation' => $request->input('password_confirmation'),
                    'token' => $validated['token'],
                ],
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil diperbarui. Silakan login menggunakan password baru.'
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => __($status)
            ], 400);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui password.'
            ], 500);
        }
    }
}