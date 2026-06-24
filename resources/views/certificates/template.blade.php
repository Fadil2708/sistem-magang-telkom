<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat Resmi Telkom Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #e2e8f0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* ===================== KANVAS UTAMA ===================== */
        .cert {
            width: 287mm;
            height: 200mm;
            background: #fdfaf4;
            position: relative;
            font-family: 'Inter', sans-serif;
            color: #1a1a1a;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }

        /* ===================== STRIPE MERAH TELKOM ===================== */
        .top-stripe,
        .bottom-stripe {
            position: absolute;
            left: 0;
            right: 0;
            height: 7px;
            background: #c8000a;
            z-index: 10;
        }
        .top-stripe    { top: 0; }
        .bottom-stripe { bottom: 0; }

        /* ===================== BORDER GANDA EMAS ===================== */
        .outer-border {
            position: absolute;
            inset: 11px;
            border: 2px solid #c8a96e;
            pointer-events: none;
            z-index: 2;
        }

        .inner-border {
            position: absolute;
            inset: 15px;
            border: 0.5px solid #e8d4a0;
            pointer-events: none;
            z-index: 2;
        }

        /* ===================== ORNAMEN SUDUT ===================== */
        .corner-orn {
            position: absolute;
            width: 36px;
            height: 36px;
            z-index: 3;
        }
        .c-tl { top: 8px;  left: 8px; }
        .c-tr { top: 8px;  right: 8px;  transform: scaleX(-1); }
        .c-bl { bottom: 8px; left: 8px;  transform: scaleY(-1); }
        .c-br { bottom: 8px; right: 8px;  transform: scale(-1,-1); }

        /* ===================== WATERMARK ===================== */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
            pointer-events: none;
            z-index: 1;
            width: 260px;
            height: 260px;
        }

        /* ===================== BODY KONTEN ===================== */
        .cert-body {
            position: relative;
            z-index: 4;
            padding: 22px 52px 18px 52px;
        }

        /* ===================== HEADER ===================== */
        .cert-header {
            width: 100%;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #d4b97a;
        }

        .cert-header table {
            width: 100%;
            border-collapse: collapse;
        }

        .cert-header td {
            vertical-align: middle;
        }

        .logo-td {
            width: auto;
        }

        .logo-td-inner {
            display: inline-block;
            margin-right: 11px;
            vertical-align: middle;
        }

        .number-td {
            text-align: right;
            width: 40%;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            object-fit: contain;
        }

        .logo-text h2 {
            margin: 0 0 1px 0;
            font-family: 'Cinzel', serif;
            font-size: 16px;
            font-weight: 700;
            color: #c8000a;
            letter-spacing: 0.8px;
            line-height: 1.2;
        }

        .logo-text p {
            margin: 0;
            font-size: 10px;
            font-weight: 500;
            color: #7a6a4a;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .cert-number-block {
            text-align: right;
        }

        .cert-number-block .label {
            font-size: 9px;
            color: #9a8a6a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
        }

        .cert-number-block .number {
            font-size: 11px;
            color: #5a4a2a;
            font-weight: 600;
            margin-top: 2px;
        }

        /* ===================== ORNAMEN DIVIDER ===================== */
        .divider-ornament {
            text-align: center;
            color: #c8a96e;
            font-size: 14px;
            letter-spacing: 8px;
            line-height: 1;
            margin: 0 0 8px 0;
        }

        /* ===================== JUDUL UTAMA ===================== */
        .cert-title-block {
            text-align: center;
            margin-bottom: 4px;
        }

        .cert-title-block .kop {
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: #9a8a6a;
            margin-bottom: 4px;
        }

        .cert-title-block .main-title {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            font-weight: 700;
            color: #1a0a00;
            letter-spacing: 5px;
            text-transform: uppercase;
            line-height: 1.1;
            margin: 0 0 3px 0;
        }

        .cert-title-block .sub-title {
            font-family: 'EB Garamond', serif;
            font-style: italic;
            font-size: 13px;
            color: #c8000a;
            letter-spacing: 1px;
            margin: 0;
        }

        /* ===================== INTRO ===================== */
        .intro-text {
            text-align: center;
            font-family: 'EB Garamond', serif;
            font-size: 13px;
            color: #6a5a3a;
            margin: 10px 0 8px 0;
            font-style: italic;
        }

        /* ===================== TABEL IDENTITAS ===================== */
        .profile-table-wrap {
            margin: 0 auto 10px auto;
            max-width: 520px;
            background: rgba(200,169,110,0.07);
            border: 0.5px solid #d4b97a;
            padding: 10px 22px;
        }

        .ptable {
            width: 100%;
            border-collapse: collapse;
        }

        .ptable tr td {
            padding: 4px 0;
            font-size: 12px;
            color: #2a1a00;
            vertical-align: middle;
        }

        .ptable .col-label {
            width: 26%;
            color: #7a6a4a;
            font-weight: 500;
            font-size: 11px;
        }

        .ptable .col-colon {
            width: 4%;
            text-align: center;
            color: #9a8070;
        }

        .ptable .col-value {
            width: 70%;
            font-weight: 600;
            color: #1a0a00;
        }

        .ptable .name-value {
            font-family: 'Cinzel', serif;
            font-size: 17px;
            font-weight: 700;
            color: #c8000a;
            letter-spacing: 0.8px;
        }

        .ptable .name-row td {
            padding-bottom: 7px;
            border-bottom: 1px solid #d4b97a;
        }

        .ptable tr:not(.name-row) td {
            padding-top: 6px;
        }

        /* ===================== DEKLARASI ===================== */
        .declaration {
            font-family: 'EB Garamond', serif;
            font-size: 13px;
            color: #3a2a10;
            text-align: center;
            line-height: 1.65;
            max-width: 580px;
            margin: 0 auto 14px auto;
        }

        .declaration strong {
            font-weight: 600;
            color: #1a0a00;
        }

        /* ===================== SECTION TANDA TANGAN ===================== */
        .sig-section {
            width: 100%;
            padding-top: 10px;
            border-top: 1px solid #d4b97a;
        }

        .sig-section table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .sig-section td {
            text-align: center;
            vertical-align: bottom;
            width: 33%;
            padding: 0 10px;
        }

        .sig-space {
            height: 38px;
        }

        .sig-line {
            width: 120px;
            height: 1px;
            background: #9a8a6a;
            margin: 0 auto 4px auto;
        }

        .sig-name {
            font-size: 10px;
            font-weight: 600;
            color: #1a0a00;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .sig-role {
            font-size: 9px;
            color: #7a6a4a;
            margin-top: 2px;
            font-style: italic;
        }

        .sig-date {
            font-family: 'EB Garamond', serif;
            font-size: 11px;
            color: #6a5a3a;
            margin-bottom: 6px;
            font-style: italic;
        }

        /* ===================== QR CODE ===================== */
        .qr-box {
            width: 56px;
            height: 56px;
            border: 1px solid #c8a96e;
            margin: 0 auto 4px auto;
            text-align: center;
            line-height: 56px;
            background: #fff;
            padding: 3px;
        }

        .qr-box svg,
        .qr-box img {
            width: 100%;
            height: 100%;
        }

        .qr-label {
            font-size: 8px;
            color: #9a8a6a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="cert">

    <!-- Stripe atas & bawah merah Telkom -->
    <div class="top-stripe"></div>
    <div class="bottom-stripe"></div>

    <!-- Border ganda emas -->
    <div class="outer-border"></div>
    <div class="inner-border"></div>

    <!-- Ornamen sudut -->
    <svg class="corner-orn c-tl" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 2 L16 2" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M2 2 L2 16" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M5 5 L14 5" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <path d="M5 5 L5 14" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <circle cx="2" cy="2" r="2.2" fill="#c8a96e"/>
        <circle cx="9" cy="9" r="1.3" fill="#c8a96e" opacity="0.5"/>
    </svg>
    <svg class="corner-orn c-tr" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 2 L16 2" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M2 2 L2 16" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M5 5 L14 5" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <path d="M5 5 L5 14" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <circle cx="2" cy="2" r="2.2" fill="#c8a96e"/>
        <circle cx="9" cy="9" r="1.3" fill="#c8a96e" opacity="0.5"/>
    </svg>
    <svg class="corner-orn c-bl" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 2 L16 2" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M2 2 L2 16" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M5 5 L14 5" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <path d="M5 5 L5 14" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <circle cx="2" cy="2" r="2.2" fill="#c8a96e"/>
        <circle cx="9" cy="9" r="1.3" fill="#c8a96e" opacity="0.5"/>
    </svg>
    <svg class="corner-orn c-br" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 2 L16 2" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M2 2 L2 16" stroke="#c8a96e" stroke-width="2" fill="none"/>
        <path d="M5 5 L14 5" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <path d="M5 5 L5 14" stroke="#c8a96e" stroke-width="0.7" fill="none" opacity="0.7"/>
        <circle cx="2" cy="2" r="2.2" fill="#c8a96e"/>
        <circle cx="9" cy="9" r="1.3" fill="#c8a96e" opacity="0.5"/>
    </svg>

    <!-- Watermark latar -->
    <svg class="watermark" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="45" stroke="#c8000a" stroke-width="1.5" fill="none"/>
        <circle cx="50" cy="50" r="38" stroke="#c8000a" stroke-width="0.5" fill="none"/>
        <circle cx="50" cy="50" r="28" stroke="#c8a96e" stroke-width="0.8" fill="none"/>
        <line x1="5"  y1="50" x2="95" y2="50" stroke="#c8000a" stroke-width="0.3"/>
        <line x1="50" y1="5"  x2="50" y2="95" stroke="#c8000a" stroke-width="0.3"/>
        <line x1="20" y1="20" x2="80" y2="80" stroke="#c8000a" stroke-width="0.3"/>
        <line x1="80" y1="20" x2="20" y2="80" stroke="#c8000a" stroke-width="0.3"/>
        <circle cx="50" cy="50" r="3.5" fill="#c8000a" opacity="0.5"/>
    </svg>

    <!-- Konten sertifikat -->
    <div class="cert-body">

        <!-- Header: Logo + Nomor -->
        <div class="cert-header">
            <table>
                <tr>
                    <td class="logo-td">
                        <div class="logo-td-inner">
                            <img class="logo-icon" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/TLK.png'))) }}" alt="Telkom Indonesia">
                        </div>
                        <div class="logo-text" style="display:inline-block;vertical-align:middle">
                            <h2>Telkom Indonesia</h2>
                            <p>Witel Sukabumi</p>
                        </div>
                    </td>
                    <td class="number-td">
                        <div class="cert-number-block">
                            <div class="label">Nomor Sertifikat</div>
                            <div class="number">{{ $certificate->certificate_number }}</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Ornamen pemisah -->
        <div class="divider-ornament">— ✦ —</div>

        <!-- Judul -->
        <div class="cert-title-block">
            <div class="kop">PT. Telekomunikasi Indonesia, Tbk.</div>
            <div class="main-title">Sertifikat Penghargaan</div>
            <div class="sub-title">Certificate of Appreciation</div>
        </div>

        <!-- Teks intro -->
        <p class="intro-text">Diberikan secara resmi dan dengan bangga kepada :</p>

        <!-- Tabel identitas -->
        <div class="profile-table-wrap">
            <table class="ptable">
                <tr class="name-row">
                    <td class="col-label">Nama Lengkap</td>
                    <td class="col-colon">:</td>
                    <td class="col-value name-value">{{ $certificate->intern->internProfile?->full_name }}</td>
                </tr>
                <tr>
                    <td class="col-label">Asal Instansi</td>
                    <td class="col-colon">:</td>
                    <td class="col-value">{{ $certificate->intern->internProfile?->institution_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Penempatan</td>
                    <td class="col-colon">:</td>
                    <td class="col-value">{{ $certificate->internship->vacancy?->title ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="col-label">Nilai Akhir</td>
                    <td class="col-colon">:</td>
                    <td class="col-value">{{ $certificate->final_score }} (Grade {{ $certificate->grade }})</td>
                </tr>
            </table>
        </div>

        <!-- Teks deklarasi -->
        <p class="declaration">
            Atas integritas, dedikasi, dan kontribusi aktifnya selama melaksanakan program
            <strong>Praktik Kerja Lapangan (PKL) / Magang Industri</strong>
            di PT. Telekomunikasi Indonesia, Tbk., terhitung sejak
            <strong>{{ $certificate->internship->actual_start_date?->isoFormat('D MMMM Y') ?? '-' }}</strong>
            sampai dengan
            <strong>{{ $certificate->internship->actual_end_date?->isoFormat('D MMMM Y') ?? '-' }}</strong>.
        </p>

        <!-- Tanda Tangan -->
        <div class="sig-section">
            <table>
                <tr>

                    <!-- Kiri: Pembimbing -->
                    <td>
                        <div class="sig-space"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">{{ $certificate->internship->supervisor?->supervisorProfile?->full_name ?? 'Pembimbing Lapangan' }}</div>
                        <div class="sig-role">Pembimbing Lapangan</div>
                    </td>

                    <!-- Tengah: QR Code -->
                    <td>
                        <div class="qr-box">
                            {!! $qrCode !!}
                        </div>
                        <div class="qr-label">Verifikasi Sertifikat</div>
                    </td>

                    <!-- Kanan: Manager -->
                    <td>
                        <div class="sig-date">Sukabumi, {{ $certificate->issued_at?->isoFormat('D MMMM Y') }}</div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Manager Human Capital</div>
                        <div class="sig-role">Manager Human Capital</div>
                    </td>

                </tr>
            </table>
        </div>

    </div><!-- /cert-body -->

</div><!-- /cert -->

</body>
</html>