{{--
-- Cabeçalho padrão para inserção no todo de todos os PDFs exportados
--}}
<div style="width: 100%">
    <div style="text-align: center;">
        <h1>{{ __('system.pdf.title') }}</h1>
    </div>
    <div>
        <p style="margin-top:0; margin-bottom: 3px;">{{ __('system.pdf.performed_by') }}: ...</p>
        <p style="margin-top:0; margin-bottom: 3px;">{{ __('system.pdf.patient') }}: ...</p>
        <p style="margin-top:0; margin-bottom: 3px;">{{ __('system.pdf.company') }}: ...</p>
        <table width="100%">
            <tr>
                <td style="width: 20%">{{ __('system.pdf.age') }}: ...</td>
                <td style="width: 20%">{{ __('system.pdf.sex') }}: ...</td>
                <td style="width: 30%">{{ __('system.pdf.medical_record_number') }}: 0000</td>
                <td style="width: 30%">{{ __('system.pdf.date') }}: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</td>
            </tr>
        </table>
    </div>
</div>
