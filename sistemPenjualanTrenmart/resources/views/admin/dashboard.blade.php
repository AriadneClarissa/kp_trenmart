<h1>Panel Admin - Approval Pelanggan</h1>

<table border="1">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Tipe</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    @foreach($pendingUsers as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->customer_type }}</td>
        <td>Belum Disetujui</td>
        <td>
            <form action="/admin/approve/{{ $user->id }}" method="POST">
                @csrf
                <button type="submit">Setujui Jadi Langganan</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>