@extends('layouts.public')
@section('title', 'Verifikasi Sertifikat')

@section('content')
<div style="max-width:480px;margin:40px auto;padding:0 16px">

    @if($certificate ?? false)
    <div style="text-align:center;margin-bottom:20px">
        <div style="width:56px;height:56px;background:#ECFDF5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#16A34A">
            <i class="ti ti-circle-check"></i>
        </div>
        <div style="font-size:16px;font-weight:700;color:#065F46">Sertifikat Valid & Terverifikasi</div>
        <div style="font-size:12px;color:#5C5A55;margin-top:4px">
            Dokumen ini diterbitkan resmi oleh Telkom Sukabumi
        </div>
    </div>

    <div class="panel overflow-x-auto">
        <div style="background:linear-gradient(135deg,#0F172A,#1E293B);padding:20px;text-align:center">
            <div style="font-size:20px;font-weight:700;color:#fff">
                {{ $certificate->intern->internProfile->full_name ?? $certificate->intern->email ?? '—' }}
            </div>
            <div style="font-size:11px;color:rgba(255,255,255,0.5);margin-top:4px">
                {{ $certificate->intern->internProfile->institution_name ?? '—' }}
            </div>
        </div>
        <div style="padding:16px">
            <table style="width:100%;font-size:12px;border-collapse:collapse">
                @foreach([
                    ['label' => 'No. Sertifikat', 'value' => $certificate->certificate_number, 'mono' => true],
                    ['label' => 'Posisi Magang', 'value' => $certificate->internship->vacancy->title ?? '—'],
                    ['label' => 'Divisi', 'value' => $certificate->internship->vacancy->division ?? '—'],
                    ['label' => 'Periode', 'value' => $certificate->internship->actual_start_date?->format('d M Y') . ' — ' . $certificate->internship->actual_end_date?->format('d M Y')],
                    ['label' => 'Nilai Akhir', 'value' => $certificate->final_score . ' (Grade ' . $certificate->grade . ')'],
                    ['label' => 'Diterbitkan', 'value' => $certificate->issued_at?->format('d F Y')],
                ] as $row)
                <tr style="border-bottom:1px solid #E8E6E1">
                    <td style="padding:8px 0;color:#5C5A55;width:40%">{{ $row['label'] }}</td>
                    <td style="padding:8px 0;color:#1E1C1A;font-weight:700;word-break:break-all;font-family:{{ isset($row['mono']) ? 'monospace' : 'inherit' }}">
                        {{ $row['value'] }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    @else
    <div style="text-align:center;padding:40px 20px">
        <div style="width:56px;height:56px;background:#FEF2F2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:28px;color:#DC2626">
            <i class="ti ti-circle-x"></i>
        </div>
        <div style="font-size:16px;font-weight:700;color:#991B1B">Sertifikat Tidak Valid</div>
        <div style="font-size:12px;color:#5C5A55;margin-top:8px;line-height:1.6">
            Token verifikasi tidak ditemukan atau sudah tidak berlaku.
            Pastikan QR Code yang dipindai berasal dari sertifikat resmi Telkom Sukabumi.
        </div>
    </div>
    @endif

</div>
@endsection
