<div class="grid grid-cols-[8rem_1fr] items-center gap-4">

    <label class="text-right" for="">Date of Birth</label>

    <div class="flex justify-evenly">
        <select name="day" id="">
        <option value="">Day</option>
            @for ($d = 1; $d <= 31; $d++)
                <option class="text-black" value="{{ $d }}">{{ $d }}</option>
            @endfor

        </select>

        <select name="month" id="">
            <option value="">Month</option>
                @foreach (range(1, 12) as $m)
                    <option class="text-black" value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                @endforeach
        </select>

        <select name="year" id="">
            <option value="">Year</option>
            @for ($y = now()->year; $y >= 1900; $y--)
                <option class="text-black" value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>

</div>
<x-forms.form-error class="text-center" error="year" />
<x-forms.form-error class="text-center" error="month" />
<x-forms.form-error class="text-center" error="day" />
