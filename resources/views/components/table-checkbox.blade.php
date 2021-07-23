<table class="table table-bordered">
    <thead>
        <tr>
            <th>Điều kiện</th>
            @foreach ($fields ?? [] as $key => $value)
                <th scope="col">{{ __("title.$key") }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    <tr>

    @foreach ($items ?? [] as $item)
            <tr>
                <td>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="check" class="custom-control-input" id="check" value="">
                        <label class="custom-control-label" for="check"></label>
                    </div>
                </td>
            @foreach ($fields ?? [] as $key => $value)

                @if ($value === 'pattern.modified')
                    <td><a href="{{ route($edit_route ?? 'edit', ['id' => $item->id]) }}"
                           class="btn btn-primary">{{ __('Sửa') }}</a>
                    </td>
                @elseif ($value === 'pattern.image')
                    <td>
                        @if($item->image)
                            <img src="{{ $item->image }}" alt="" srcset="" width="50" height="50">
                        @endif
                    </td>
                @elseif (strpos($value, 'custom.'))
                    @yield($value, $item)
                @else
                    <td>
                        {{ isset($$value) && array_key_exists($item->$value, $$value) ? $$value[$item->$value] : $item->$value }}
                    </td>
                @endif
                @if($value === 'pattern.id')
                    <td scope="row">{{ $item->id }}</td>
                @endif
            @endforeach
        </tr>
        @endforeach
    </tr>

    </tbody>
</table>