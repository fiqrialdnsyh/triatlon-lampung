<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminNewRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * $data harus berisi:
     * - jenis: 'Pelatihan' | 'Event Open' | 'Event Kejurnas'
     * - nama_kegiatan: judul pelatihan/event
     * - nama_pendaftar: nama lengkap peserta
     * - detail: array asosiatif field tambahan (usia, kategori, golongan biaya, dll)
     * - link_verifikasi: URL menuju halaman verifikasi
     * - waktu_daftar: string waktu pendaftaran
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Pendaftaran Baru: ' . $this->data['nama_kegiatan'] . ' — ' . $this->data['nama_pendaftar'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-registration',
            with: ['data' => $this->data],
        );
    }
}
