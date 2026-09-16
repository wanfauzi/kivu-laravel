<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Bidang :attribute harus diterima.',
    'accepted_if' => 'Bidang :attribute harus diterima ketika :other adalah :value.',
    'active_url' => 'Bidang :attribute bukan URL yang valid.',
    'after' => 'Bidang :attribute harus tanggal setelah :date.',
    'after_or_equal' => 'Bidang :attribute harus tanggal setelah atau sama dengan :date.',
    'alpha' => 'Bidang :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Bidang :attribute hanya boleh berisi huruf, angka, garis miring, dan garis bawah.',
    'alpha_num' => 'Bidang :attribute hanya boleh berisi huruf dan angka.',
    'any_of' => 'Bidang :attribute tidak valid.',
    'array' => 'Bidang :attribute harus berupa array.',
    'array_keys' => 'Bidang :attribute hanya boleh berisi kunci berikut: :values.',
    'ascii' => 'Bidang :attribute hanya boleh berisi karakter dan simbol alfanumerik satu byte.',
    'base64' => 'Bidang :attribute harus berupa string Base64 yang valid.',
    'before' => 'Bidang :attribute harus tanggal sebelum :date.',
    'before_or_equal' => 'Bidang :attribute harus tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => 'Bidang :attribute harus memiliki antara :min sampai :max item.',
        'file' => 'Bidang :attribute harus antara :min sampai :max kilobyte.',
        'numeric' => 'Bidang :attribute harus antara :min sampai :max.',
        'string' => 'Bidang :attribute harus antara :min sampai :max karakter.',
    ],
    'boolean' => 'Bidang :attribute harus benar atau salah.',
    'can' => 'Bidang :attribute berisi nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'contains' => 'Bidang :attribute kehilangan nilai yang diperlukan.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Bidang :attribute bukan tanggal yang valid.',
    'date_equals' => 'Bidang :attribute harus tanggal yang sama dengan :date.',
    'date_format' => 'Bidang :attribute tidak sesuai format :format.',
    'decimal' => 'Bidang :attribute harus memiliki :decimal tempat desimal.',
    'declined' => 'Bidang :attribute harus ditolak.',
    'declined_if' => 'Bidang :attribute harus ditolak ketika :other adalah :value.',
    'different' => 'Bidang :attribute dan :other harus berbeda.',
    'digits' => 'Bidang :attribute harus :digits digit.',
    'digits_between' => 'Bidang :attribute harus antara :min dan :max digit.',
    'dimensions' => 'Bidang :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Bidang :attribute memiliki nilai ganda.',
    'doesnt_contain' => 'Bidang :attribute tidak boleh mengandung salah satu dari: :values.',
    'doesnt_end_with' => 'Bidang :attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with' => 'Bidang :attribute tidak boleh dimulai dengan salah satu dari: :values.',
    'email' => 'Bidang :attribute harus berupa alamat email yang valid.',
    'encoding' => 'Bidang :attribute harus dikodekan dalam :encoding.',
    'ends_with' => 'Bidang :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => ':attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih tidak valid.',
    'extensions' => 'Bidang :attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => 'Bidang :attribute harus berupa berkas.',
    'filled' => 'Bidang :attribute harus memiliki nilai.',
    'gt' => [
        'array' => 'Bidang :attribute harus memiliki lebih dari :value item.',
        'file' => 'Bidang :attribute harus lebih dari :value kilobyte.',
        'numeric' => 'Bidang :attribute harus lebih besar dari :value.',
        'string' => 'Bidang :attribute harus lebih dari :value karakter.',
    ],
    'gte' => [
        'array' => 'Bidang :attribute harus memiliki :value item atau lebih.',
        'file' => 'Bidang :attribute harus lebih besar dari atau sama dengan :value kilobyte.',
        'numeric' => 'Bidang :attribute harus lebih besar dari atau sama dengan :value.',
        'string' => 'Bidang :attribute harus lebih besar dari atau sama dengan :value karakter.',
    ],
    'hex_color' => 'Bidang :attribute harus berupa warna heksadesimal yang valid.',
    'image' => 'Bidang :attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak valid.',
    'in_array' => 'Bidang :attribute harus ada dalam :other.',
    'in_array_keys' => 'Bidang :attribute harus mengandung setidaknya satu dari kunci berikut: :values.',
    'integer' => 'Bidang :attribute harus berupa bilangan bulat.',
    'ip' => 'Bidang :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Bidang :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Bidang :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Bidang :attribute harus berupa string JSON yang valid.',
    'list' => 'Bidang :attribute harus berupa daftar.',
    'lowercase' => 'Bidang :attribute harus huruf kecil.',
    'lt' => [
        'array' => 'Bidang :attribute harus memiliki kurang dari :value item.',
        'file' => 'Bidang :attribute harus kurang dari :value kilobyte.',
        'numeric' => 'Bidang :attribute harus kurang dari :value.',
        'string' => 'Bidang :attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Bidang :attribute tidak boleh memiliki lebih dari :value item.',
        'file' => 'Bidang :attribute harus kurang dari atau sama dengan :value kilobyte.',
        'numeric' => 'Bidang :attribute harus kurang dari atau sama dengan :value.',
        'string' => 'Bidang :attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => 'Bidang :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => 'Bidang :attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Bidang :attribute tidak boleh lebih dari :max kilobyte.',
        'numeric' => 'Bidang :attribute tidak boleh lebih dari :max.',
        'string' => 'Bidang :attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits' => 'Bidang :attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => 'Bidang :attribute harus berupa berkas dengan tipe: :values.',
    'mimetypes' => 'Bidang :attribute harus berupa berkas dengan tipe: :values.',
    'min' => [
        'array' => 'Bidang :attribute harus memiliki minimal :min item.',
        'file' => 'Bidang :attribute harus minimal :min kilobyte.',
        'numeric' => 'Bidang :attribute harus minimal :min.',
        'string' => 'Bidang :attribute harus minimal :min karakter.',
    ],
    'min_digits' => 'Bidang :attribute harus memiliki minimal :min digit.',
    'missing' => 'Bidang :attribute harus kosong.',
    'missing_if' => 'Bidang :attribute harus kosong ketika :other adalah :value.',
    'missing_unless' => 'Bidang :attribute harus kosong kecuali :other dalam :values.',
    'missing_with' => 'Bidang :attribute harus kosong ketika :values tersedia.',
    'missing_with_all' => 'Bidang :attribute harus kosong ketika :values tersedia.',
    'multiple_of' => 'Bidang :attribute harus merupakan kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih tidak valid.',
    'not_regex' => 'Format bidang :attribute tidak valid.',
    'numeric' => 'Bidang :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Bidang :attribute harus mengandung setidaknya satu huruf.',
        'mixed' => 'Bidang :attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Bidang :attribute harus mengandung setidaknya satu angka.',
        'symbols' => 'Bidang :attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Pilih :attribute yang berbeda.',
    ],
    'present' => 'Bidang :attribute harus tersedia.',
    'present_if' => 'Bidang :attribute harus tersedia ketika :other adalah :value.',
    'present_unless' => 'Bidang :attribute harus tersedia kecuali :other dalam :values.',
    'present_with' => 'Bidang :attribute harus tersedia ketika :values tersedia.',
    'present_with_all' => 'Bidang :attribute harus tersedia ketika :values tersedia.',
    'prohibited' => 'Bidang :attribute dilarang.',
    'prohibited_if' => 'Bidang :attribute dilarang ketika :other adalah :value.',
    'prohibited_if_accepted' => 'Bidang :attribute dilarang ketika :other diterima.',
    'prohibited_if_declined' => 'Bidang :attribute dilarang ketika :other ditolak.',
    'prohibited_unless' => 'Bidang :attribute dilarang kecuali :other dalam :values.',
    'prohibits' => 'Bidang :attribute melarang :other tersedia.',
    'regex' => 'Format bidang :attribute tidak valid.',
    'required' => 'Bidang :attribute wajib diisi.',
    'required_array_keys' => 'Bidang :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Bidang :attribute wajib diisi ketika :other adalah :value.',
    'required_if_accepted' => 'Bidang :attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => 'Bidang :attribute wajib diisi ketika :other ditolak.',
    'required_unless' => 'Bidang :attribute wajib diisi kecuali :other dalam :values.',
    'required_with' => 'Bidang :attribute wajib diisi ketika :values tersedia.',
    'required_with_all' => 'Bidang :attribute wajib diisi ketika :values tersedia.',
    'required_without' => 'Bidang :attribute wajib diisi ketika :values tidak tersedia.',
    'required_without_all' => 'Bidang :attribute wajib diisi ketika tidak ada :values yang tersedia.',
    'same' => 'Bidang :attribute dan :other harus sama.',
    'size' => [
        'array' => 'Bidang :attribute harus berisi :size item.',
        'file' => 'Bidang :attribute harus :size kilobyte.',
        'numeric' => 'Bidang :attribute harus :size.',
        'string' => 'Bidang :attribute harus :size karakter.',
    ],
    'starts_with' => 'Bidang :attribute harus dimulai dengan salah satu dari: :values.',
    'string' => 'Bidang :attribute harus berupa teks.',
    'timezone' => 'Bidang :attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => 'Bidang :attribute harus huruf besar.',
    'url' => 'Bidang :attribute harus berupa URL yang valid.',
    'ulid' => 'Bidang :attribute harus berupa ULID yang valid.',
    'uuid' => 'Bidang :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "Email" instead of "email".
    |
    */

    'attributes' => [
        'title' => 'judul',
        'description' => 'deskripsi',
        'budget' => 'budget',
        'min_budget' => 'budget minimal',
        'max_budget' => 'budget maksimal',
        'category_id' => 'kategori',
        'due_date' => 'deadline',
        'skillIds' => 'keahlian',
        'bid_amount' => 'penawaran harga',
        'message' => 'pesan',
        'new_skill' => 'keahlian baru',
        'name' => 'nama',
        'email' => 'email',
        'password' => 'kata sandi',
        'phone' => 'nomor telepon',
        'university' => 'universitas',
        'major' => 'program studi',
        'nim' => 'NIM',
        'company' => 'nama perusahaan',
        'rejection_note' => 'catatan alasan',
        'revision_note' => 'catatan revisi',
        'rating' => 'penilaian',
        'comment' => 'komentar',
        'amount' => 'jumlah',
        'price' => 'harga',
        'file' => 'berkas',
        'link' => 'tautan',
        'note' => 'catatan',
        'profile_picture' => 'foto profil',
        'reason' => 'alasan',
    ],

];