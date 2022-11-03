<a href={{ '/download' }}>DL</a>

<table border="1">
    <tr>
        <th>施設名</th>
        <th>都道府県</th>
        <th>URL</th>
    </tr>
    @foreach ($saunaInfo as $val)
        <tr>
            <td>{{ $val[0] }}</td>
            <td>{{ $val[1] }}</td>
            <td><a href="{{ $val[2] }}">{{ $val[2] }}</a></td>
        </tr>
    @endforeach
</table>
