<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * $data harus berisi:
     * - status: 'Diterima' | 'Valid' | 'Ditolak'
     * - jenis: 'Pelatihan' | 'Event Open' | 'Event Kejurnas'
     * - nama_kegiatan: judul pelatihan/event
     * - nama_pendaftar: nama lengkap peserta
     * - alasan_penolakan: string|null (hanya jika ditolak)
     * - link_terkait: URL (tiket QR / grup WA / halaman detail)
     * - link_label: label tombol (misal "Cetak Tiket QR")
     * - detail: array asosiatif field tambahan (tanggal pelaksanaan, lokasi, dll)
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        $isDiterima = in_array($this->data['status'], ['Diterima', 'Valid']);

        return new Envelope(
            subject: ($isDiterima ? '✅ Pendaftaran Diterima: ' : '❌ Pendaftaran Ditolak: ') . $this->data['nama_kegiatan'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.registration-status',
            with: ['data' => $this->data],
        );
    }
}
