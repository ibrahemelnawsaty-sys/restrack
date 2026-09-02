{{--
  Server-side PDF certificate (dompdf). Deliberately standalone: dompdf understands
  neither the app layout's Vite bundle nor CSS custom properties, flexbox or grid — so this
  template repeats the approved artwork with plain colours, absolute positioning and tables.
  Keep it in sync with resources/views/student/certificate.blade.php.

  Arabic: dompdf does no bidi reordering and no Arabic contextual shaping, so every Arabic
  string arrives here already shaped + reordered by App\Services\CertificatePdfService, and
  the composition deliberately keeps each line to a single script (no mixed-direction lines).
--}}
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{{ $certificate->number }}</title>
<style>
    @page { margin: 0; }
    * { margin: 0; padding: 0; }
    body { background-color: #FCFAF4; color: #16264B;
           font-family: "{{ $font }}", "DejaVu Sans", sans-serif; font-size: 11pt; }

    /* navy corner panels, with the gold double rule drawn over them */
    .cnr    { position: absolute; width: 104pt; height: 104pt; background-color: #16264B; }
    .cnr.tl { top: 0; left: 0; }
    .cnr.tr { top: 0; right: 0; }
    .cnr.bl { bottom: 0; left: 0; }
    .cnr.br { bottom: 0; right: 0; }
    .frame  { position: absolute; top: 16pt; left: 16pt; right: 16pt; bottom: 16pt;
              border: 2pt solid #8A6C1F; }
    .frame2 { position: absolute; top: 22pt; left: 22pt; right: 22pt; bottom: 22pt;
              border: 0.7pt solid #BFA155; }

    .sheet  { position: absolute; top: 40pt; left: 56pt; right: 56pt; text-align: center; }

    .nm     { font-size: 11pt; font-weight: bold; letter-spacing: 3pt; color: #16264B; padding-top: 5pt; }
    .tag    { font-size: 8pt; color: #4E5A7C; padding-top: 3pt; }

    .title  { font-family: "Times New Roman", serif; font-size: 38pt; font-weight: bold;
              letter-spacing: 6pt; color: #8A6C1F; padding-top: 14pt; }
    .sub    { font-size: 11pt; font-weight: bold; letter-spacing: 7pt; color: #A9862A; padding-top: 5pt; }
    .ar     { font-size: 13pt; font-weight: bold; color: #A9862A; padding-top: 7pt; }
    .rule   { width: 200pt; height: 1pt; background-color: #8A6C1F; margin: 11pt auto 0; }

    .lead   { font-size: 9.5pt; color: #4E5A7C; padding-top: 12pt; }
    .leadar { font-size: 9.5pt; color: #4E5A7C; padding-top: 2pt; }
    .name   { font-family: "Times New Roman", serif; font-style: italic; font-size: 29pt;
              font-weight: bold; color: #8A6C1F; padding-top: 5pt; }
    .namear { font-size: 25pt; font-weight: bold; color: #8A6C1F; padding-top: 5pt; }
    .track  { font-size: 12.5pt; font-weight: bold; color: #16264B; padding-top: 5pt; }
    .trackar{ font-size: 12pt; font-weight: bold; color: #16264B; padding-top: 2pt; }
    .meta   { font-size: 9.5pt; color: #4E5A7C; padding-top: 12pt; }
    .meta b { color: #8A6C1F; }

    .bottom  { position: absolute; bottom: 44pt; left: 62pt; right: 62pt; }
    .bottom td { vertical-align: bottom; font-size: 7.5pt; color: #4E5A7C; }
    .cap     { padding-top: 4pt; }
    .sigline { border-top: 1pt solid #16264B; width: 160pt; margin: 0 0 0 auto; }
    .signm   { font-size: 9.5pt; font-weight: bold; color: #16264B; padding-top: 5pt; }
    .sigen   { font-size: 8pt; color: #4E5A7C; padding-top: 1pt; }

    .foot    { position: absolute; bottom: 25pt; left: 62pt; right: 62pt;
               font-size: 7pt; color: #4E5A7C; text-align: center; }
</style>
</head>
<body>
    <div class="cnr tl"></div><div class="cnr tr"></div>
    <div class="cnr bl"></div><div class="cnr br"></div>
    <div class="frame"></div><div class="frame2"></div>

    <div class="sheet">
        {{-- the 3-bar brand mark, redrawn as plain blocks: dompdf's inline-SVG support is partial --}}
        <table style="margin:0 auto;border-collapse:collapse"><tr>
            <td style="width:10pt;height:24pt;vertical-align:bottom"><div style="width:6pt;height:9pt;background-color:#B9932F"></div></td>
            <td style="width:10pt;height:24pt;vertical-align:bottom"><div style="width:6pt;height:16pt;background-color:#A98A55"></div></td>
            <td style="width:10pt;height:24pt;vertical-align:bottom"><div style="width:6pt;height:24pt;background-color:#8B7CFF"></div></td>
        </tr></table>
        <div class="nm">RESTRACK</div>
        <div class="tag">{{ $t['institute'] }}</div>

        <div class="title">CERTIFICATE</div>
        <div class="sub">OF COMPLETION</div>
        <div class="ar">{{ $t['certificate'] }}</div>
        <div class="rule"></div>

        <div class="lead">This is to certify that</div>
        <div class="leadar">{{ $t['certify_that'] }}</div>
        <div class="{{ $nameIsArabic ? 'namear' : 'name' }}">{{ $name }}</div>

        <div class="lead">Has successfully completed</div>
        <div class="leadar">{{ $t['completed'] }}</div>
        @if ($trackEn)
            <div class="track">{{ $trackEn }}</div>
        @endif
        @if ($trackAr)
            <div class="trackar">{{ $trackAr }}</div>
        @endif

        <div class="meta">
            Date of Completion: <b>{{ $issuedAt }}</b>
            @if ($score !== null)
                &nbsp;&nbsp;&middot;&nbsp;&nbsp; Score: <b>{{ $score }}%</b>
            @endif
        </div>
    </div>

    <table class="bottom" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            {{-- the seal sits bottom-left, exactly where the owner marked the empty area --}}
            <td width="33%" align="left">
                @if ($seal)
                    <img src="{{ $seal }}" width="78" height="80" alt="">
                @endif
                <div class="cap">{{ $t['seal'] }}</div>
            </td>

            <td width="34%" align="center">
                @if ($qr)
                    <img src="{{ $qr }}" width="82" height="82" alt="">
                @endif
                <div class="cap">Scan to verify</div>
            </td>

            {{-- Signature: a placeholder only. Drop a transparent PNG at public/images/signature.png
                 and the director's real signature renders above the rule. Nothing is fabricated,
                 and no director's name is invented — the owner supplies both. --}}
            <td width="33%" align="right">
                @if ($signature)
                    <img src="{{ $signature }}" height="32" alt="">
                @endif
                <div class="sigline"></div>
                <div class="signm">{{ $t['director'] }}</div>
                <div class="sigen">Director of Training</div>
            </td>
        </tr>
    </table>

    <div class="foot">
        {{ $certificate->number }}
        &nbsp;&nbsp;&middot;&nbsp;&nbsp; Unified No. 7053567603
        &nbsp;&nbsp;&middot;&nbsp;&nbsp; {{ $verifyUrl }}
    </div>
</body>
</html>
