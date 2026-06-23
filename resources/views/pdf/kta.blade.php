<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KTA - {{ $member->name }}</title>
    <style>
        @page {
            size: 85.6mm 53.98mm; /* Standard ID-1 Card Size */
            margin: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', 'Inter', Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F8FAFC;
            color: #1E293B;
            width: 85.6mm;
            height: 53.98mm;
            box-sizing: border-box;
            position: relative;
        }
        .card-container {
            width: 85.6mm;
            height: 53.98mm;
            padding: 3mm;
            box-sizing: border-box;
            background-image: linear-gradient(135deg, #ffffff 60%, #e6f7ed 100%);
            border: 1mm solid #D97706; /* Golden border */
            position: relative;
            overflow: hidden;
        }
        .header {
            border-bottom: 0.4mm solid #D97706;
            padding-bottom: 1mm;
            margin-bottom: 2mm;
            height: 10mm;
        }
        .logo-area {
            float: left;
            width: 12mm;
            height: 9mm;
            text-align: center;
        }
        .logo-text {
            float: left;
            margin-left: 2mm;
            width: 55mm;
        }
        .logo-text h1 {
            font-size: 8pt;
            margin: 0;
            color: #005B2B;
            font-weight: bold;
            text-transform: uppercase;
        }
        .logo-text p {
            font-size: 5pt;
            margin: 0;
            color: #D97706;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .content {
            margin-top: 1mm;
            height: 32mm;
        }
        .photo-area {
            float: left;
            width: 20mm;
            height: 26mm;
            border: 0.3mm solid #CBD5E1;
            background-color: #F1F5F9;
            overflow: hidden;
            text-align: center;
        }
        .photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .details-area {
            float: left;
            margin-left: 3mm;
            width: 38mm;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            font-size: 6.5pt;
            padding: 0.5mm 0;
            vertical-align: top;
        }
        .details-table .label {
            color: #64748B;
            width: 10mm;
        }
        .details-table .value {
            font-weight: bold;
            color: #0F172A;
        }
        .qr-area {
            float: right;
            width: 15mm;
            height: 15mm;
            margin-top: 10mm;
            text-align: right;
        }
        .qr-area svg {
            width: 100%;
            height: 100%;
        }
        .footer {
            position: absolute;
            bottom: 2mm;
            left: 3mm;
            right: 3mm;
            font-size: 4pt;
            color: #94A3B8;
            text-align: center;
            border-top: 0.1mm solid #E2E8F0;
            padding-top: 0.5mm;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="header">
            <!-- Mock Logo representing PPP Green & Golden Kaaba elements -->
            <div class="logo-area" style="background-color: #005B2B; border-radius: 1mm; color: #ffffff; font-weight: bold; line-height: 9mm; font-size: 10pt;">
                PPP
            </div>
            <div class="logo-text">
                <h1>Partai Persatuan Pembangunan</h1>
                <p>Kartu Tanda Anggota (KTA) Digital</p>
            </div>
        </div>

        <div class="content">
            <div class="photo-area">
                @if($member->photo && file_exists(public_path('storage/' . $member->photo)))
                    <img src="{{ public_path('storage/' . $member->photo) }}" alt="Photo">
                @else
                    <!-- Fallback default avatar placeholder inside the card -->
                    <div style="line-height: 26mm; font-size: 6pt; color: #94A3B8; font-weight: bold;">FOTO</div>
                @endif
            </div>

            <div class="details-area">
                <table class="details-table">
                    <tr>
                        <td class="label">Nama</td>
                        <td class="value">: {{ $member->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="value">: {{ $member->nik }}</td>
                    </tr>
                    <tr>
                        <td class="label">Wilayah</td>
                        <td class="value">: {{ $member->village->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td class="value" style="color: #005B2B;">: {{ $member->status }}</td>
                    </tr>
                </table>
            </div>

            <div class="qr-area">
                <!-- Inline SVG QR Code -->
                {!! QrCode::size(55)->margin(0)->generate($member->nik) !!}
            </div>
        </div>

        <div class="footer">
            Sistem Informasi Partai Politik PPP - Dicetak secara digital pada {{ now()->format('d-m-Y H:i') }}
        </div>
    </div>
</body>
</html>
