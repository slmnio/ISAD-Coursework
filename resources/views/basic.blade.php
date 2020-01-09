<a href="{{ route('epic') }}">epic link</a>


<table>
    <tbody>
    @foreach(\App\Drink::all() as $drink)
        <tr>
            <td>{{ $drink->id }}</td>
            <td>{{ $drink->name }}</td>
            <td>{{ $drink->description }}</td>
            <td>{{ $drink->getFormattedPrice() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>