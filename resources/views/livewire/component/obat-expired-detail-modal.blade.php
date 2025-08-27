<div>
    <flux:modal.trigger :name="'expired-list-' . $ed->id">
        <flux:button icon="arrow-right" class="bg-amber-600" variant="outline">
            Lihat Item
        </flux:button>
    </flux:modal.trigger>

    <flux:modal size="xl" class="min-w-4xl min-h-96" :name="'expired-list-' . $ed->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Daftar barang expired</flux:heading>
                <flux:text>No. {{  $ed->nomor }}</flux:text>
            </div>
            <div class="max-h-96 overflow-y-scroll rounded-box border border-base-content/5 bg-base-100 dark:bg-slate-500 dark:text-accent">
                <table class="table dark:text-accent">
                    <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Satuan</th>
                            <th>Tanggal ED</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ed->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->farmalkes->kode }}</td>
                            <td>{{ $item->farmalkes->nama }}</td>
                            <td>{{ $item->farmalkes->satuan }}</td>
                            <td>{{ $item->expire_date}}</td>
                            <td>{{ $item->qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </flux:modal>
</div>
