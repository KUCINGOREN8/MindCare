<?php

return [
    // Header
    'greeting' => 'Halo, :name!',
    'welcome_summary' => 'Selamat datang kembali, ini ringkasan praktik Anda.',

    // Stats Cards
    'stat_patients' => 'Total Pasien',
    'patients_new' => 'baru bulan ini',

    'stat_sessions' => 'Sesi Minggu Ini',
    'sessions_today' => 'hari ini',
    'sessions_completed' => 'selesai',

    'stat_revenue' => 'Pendapatan Bulanan',
    'revenue_label' => 'pendapatan', // e.g. "January revenue" -> "pendapatan Januari"
    'upcoming_sessions_title' => 'Sesi Klien Mendatang',
    'see_all' => 'Lihat semua',
    'no_upcoming_sessions' => 'Tidak ada jadwal sesi klien mendatang',
    'btn_view_clients' => 'Lihat Klien Saya',

    'history_title' => 'Riwayat Sesi',
    'status_awaiting_payment' => 'Menunggu Pembayaran',
    'unknown_patient' => 'Pasien Tidak Diketahui',
    'empty_history' => 'Tidak ada riwayat sesi ditemukan',
    'empty_history_desc' => 'Sesi yang selesai dan dibatalkan akan muncul di sini',

    // Status Labels (jika belum ada di file lain, atau bisa reuse dari psychologist_appointment.php)
    'status_confirmed' => 'Dikonfirmasi',
    'status_completed' => 'Selesai',
    'status_pending' => 'Menunggu',
    'status_cancelled' => 'Dibatalkan',

    // Gender & Age (Reuse jika mau, atau definisikan ulang)
    'female' => 'Perempuan',
    'male' => 'Laki-laki',
    'years_old' => 'tahun',

    'reschedule_title' => 'Permintaan Jadwal Ulang',
    'pending_count' => ':count Menunggu',
    'not_specified' => 'Tidak ditentukan',
    'session_id' => 'ID Sesi',

    'label_current_schedule' => 'JADWAL SAAT INI',
    'label_requested_time' => 'WAKTU YANG DIMINTA',
    'label_patient_reason' => 'ALASAN PASIEN',

    'action_required' => 'Tindakan Diperlukan:',
    'action_desc' => 'Permintaan ini menunggu tinjauan Anda. Fitur terima/tolak akan diimplementasikan pada tahap selanjutnya.',

    'empty_reschedule' => 'Tidak ada permintaan jadwal ulang yang tertunda',
    'empty_reschedule_desc' => 'Permintaan jadwal ulang pasien akan muncul di sini',

    'recent_clients_title' => 'Klien Terbaru',
    'last_session_label' => 'Terakhir:',
    'no_recent_clients' => 'Tidak ada klien terbaru ditemukan',
    'no_clients_desc' => 'Klien akan muncul di sini setelah membuat janji temu',

    'rating_title' => 'Distribusi Penilaian',
    'rating_based_on' => 'Berdasarkan :count ulasan pasien',
];