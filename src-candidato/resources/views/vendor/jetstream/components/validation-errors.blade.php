@if ($errors->any())
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">Ops! Alguma coisa deu errado.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600 list-errors">
                @foreach ($errors->all() as $error)

                    <li class="one-error">{{ $error }}</li>
                @endforeach
        </ul>
    </div>
@endif
