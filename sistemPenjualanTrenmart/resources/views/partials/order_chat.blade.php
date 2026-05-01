<div class="card mt-3 p-3">
    <h6>Obrolan Pesanan</h6>
    <div id="order-chat-{{ $order->id }}" style="max-height:300px;overflow:auto;" class="mb-3">
        @foreach($order->messages as $m)
            <div class="mb-2">
                <div class="small text-muted">{{ $m->created_at->format('d M H:i') }} · {{ $m->user ? $m->user->name : 'Admin' }}</div>
                <div class="p-2" style="background:#f7f7f9;border-radius:8px;">{{ $m->message }}</div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('orders.messages.store', $order->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input name="message" class="form-control" placeholder="Ketik pesan..." required>
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
</div>
