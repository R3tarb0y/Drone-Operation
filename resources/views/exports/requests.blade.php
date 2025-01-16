<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>{{ $request->id }}</td>
                <td>{{ $request->category }}</td>
                <td>{{ $request->created_at }}</td>
                <td>{{ $request->amount }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
