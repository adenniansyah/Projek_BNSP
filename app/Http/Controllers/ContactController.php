<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Menampilkan form kirim pesan untuk User
     */
    public function create()
    {
        return view('user.contact');
    }

    /**
     * Memproses pengiriman pesan dari User
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name'    => Auth::user()->name,
            'email'   => Auth::user()->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => 'unread',
        ]);

        return redirect()->route('contact.create')
                         ->with('success', 'Pesan Anda berhasil dikirim! Admin akan segera menindaklanjutinya.');
    }

    /**
     * ✅ METHOD BARU: Menampilkan riwayat pesan user (READ ONLY)
     */
    public function myMessages()
    {
        $userId = Auth::id();
        
        // Ambil semua pesan milik user ini, urutkan dari yang terbaru
        $contacts = Contact::where('user_id', $userId)
                           ->latest()
                           ->paginate(10);
        
        return view('user.messages.index', compact('contacts'));
    }

    /**
     * Menampilkan daftar semua pesan masuk (Khusus Admin)
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(15);
        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Menampilkan detail pesan tertentu (Khusus Admin)
     */
    public function show(Contact $contact)
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }
        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Mengubah status DAN menyimpan balasan admin - Khusus Admin
     */
    public function updateStatus(Request $request, Contact $contact)
    {
        // Validasi input
        $validated = $request->validate([
            'status'      => 'required|in:read,replied,closed',
            'admin_reply' => 'nullable|string|max:5000'
        ]);

        // Siapkan data update
        $dataToUpdate = [
            'status' => $validated['status']
        ];

        // Cek apakah ada isi balasan (trim untuk menghapus spasi kosong)
        $replyText = trim($validated['admin_reply'] ?? '');

        if (!empty($replyText)) {
            $dataToUpdate['admin_reply'] = $replyText;
            
            // Jika ada balasan, paksa status menjadi 'replied' kecuali admin memilih 'closed'
            if ($validated['status'] !== 'closed') {
                $dataToUpdate['status'] = 'replied';
            }
        }

        // Eksekusi update ke database
        $contact->update($dataToUpdate);

        return back()->with('success', 'Balasan berhasil dikirim dan status diperbarui!');
    }
}