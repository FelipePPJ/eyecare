{{--
-- Exportação de PDF com solicitações de exames
-- Realiza separação Entre "individuais", "pacotes" e "grupos".
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('system.pdf.title') }}</title>

    <style>
        html {
            font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }
    </style>
</head>
<body>
    {{-- INDIVIDUAIS - uma página por exame --}}
    @foreach ($individuals as $item)
        {{-- Componente com cabeçalho padrão --}}
        @include('components.pdf-header')

        <div class="page-break">
            {{-- <h2>{{ $item['exam']['name'] }}</h2> --}}
            <h2>{{ __('system.exams.title') }}</h2>
            <div width="100%" style="border:1px solid black; border-radius: 10px; padding: 0.1em 1em 0.3em 1em;">
                <h4 style="margin:7px 0 5px 0;">{{ $item['exam']['name'] }} @if(isset($item['exam']['laterality']) && !empty($item['exam']['laterality'])) <small> - {!! __("system.{$item['exam']['laterality']}") !!}</small> @endif</h4>
                <p style="margin:5px 0 7px 0;">
                    @if (isset($item['exam']['comment']) && !empty($item['exam']['comment']))
                        {{ __('system.form.observations_abrev') }}.: {{ $item['exam']['comment'] }}
                    @endif
                </p>
            </div>
            <p style="margin-bottom: 30px; margin-top: 6px; margin-left: 6px;">
                @if (isset($item['observations']) && !empty($item['observations']))
                    <strong>{{ __('system.form.observations') }}:</strong> {{ $item['observations'] }}
                @endif
            </p>
        </div>
        <div style="page-break-after: always;"></div>
    @endforeach

    {{-- AGRUPADOS - uma página por grupo --}}
    @foreach ($grouped as $groupId => $exams)
        {{-- Componente com cabeçalho padrão --}}
        @include('components.pdf-header')

        <div class="page-break">
            {{-- <h2>Grupo: {{ $groupId }}</h2> --}}
            <h2>{{ __('system.exams.title') }}</h2>
            @foreach ($exams as $item)

                <div width="100%" style="border:1px solid black; border-radius: 10px; padding: 0.1em 1em 0.3em 1em;">
                    @foreach ($item as $exam)
                        <h4 style="margin:7px 0 5px 0;">{{ $exam['exam']['name'] }} @if(isset($exam['exam']['laterality']) && !empty($exam['exam']['laterality'])) <small> - {!! __("system.{$exam['exam']['laterality']}") !!}</small> @endif</h4>
                        <p style="margin:5px 0 7px 0;">
                            @if (isset($exam['exam']['comment']) && !empty($exam['exam']['comment']))
                                {{ __('system.form.observations_abrev') }}.: {{ $exam['exam']['comment'] }}
                            @endif
                        </p>
                    @endforeach
                </div>
                <p style="margin-bottom: 30px; margin-top: 6px; margin-left: 6px;">
                    @if (isset($item[0]['observations']) && !empty($item[0]['observations']))
                        <strong>{{ __('system.form.observations') }}:</strong> {{ $item[0]['observations'] }}
                    @endif
                </p>

            @endforeach
        </div>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

</body>
</html>
