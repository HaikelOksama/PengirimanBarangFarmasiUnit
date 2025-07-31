<div>
    <flux:modal.trigger :name="'retur-list-' . $matreq->id">
        <flux:button icon="arrow-right" class="bg-amber-600" variant="outline">
            Lihat Item di Retur
        </flux:button>
    </flux:modal.trigger>
    <flux:modal size="xl" class="min-w-4xl min-h-96" :name="'retur-list-' . $matreq->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Daftar barang diretur</flux:heading>
                <div class="flex gap-2">
                    <flux:text class="mt-2">{{ $matreq->matreq_no }}</flux:text>

                </div>
                <flux:separator />
                <flux:spacer />
            </div>
            <div class="flex max-h-96 overflow-y-scroll gap-2 flex-wrap">
               @foreach ($matreq->retur as $retur)
                   <div class="p-2 border rounded border-zinc-300 w-full">
                    <div class="flex justify-between w-full">
                        <div class="">
                            <flux:heading>
                                No. {{ $loop->iteration }}
                            </flux:heading>
                            <flux:text>Tanggal retur : {{ $retur->tgl_retur }}</flux:text>
                        </div>
                        <div class="flex gap-2">
                            <flux:link href="{{ route('main.material-request.retur.print', $retur) }}" target="_blank"><flux:icon.printer/></flux:link>
                            <flux:icon.trash class="hover:text-red-700 hover:cursor-pointer" variant="mini"
                                wire:click="delete({{ $retur }})"
                                wire:confirm="Hapus Retur ?"></flux:icon.trash>
                        </div>
                    </div>
                    <flux:textarea rows="1" disabled label="Keterangan">{{$retur->keterangan}}</flux:textarea>
                    <flux:text>Barang - barang : </flux:text>
                    <div class="border border-base-content/5 ">
                        <table class="table table-zebra ">
                            <thead>
                                <tr class="dark:text-zinc-300">
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retur->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->farmalkes->nama }}</td>
                                        <td>{{ $item->qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                   </div>
               @endforeach
            </div>
        </div>
    </flux:modal>
</div>

@script
    <script>
        $wire.on('refresh-list', () => {
            $wire.$refresh();
        })

    </script>

@endscript

