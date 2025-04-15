<div>
    {{-- The Master doesn't talk, he acts. --}}
    <x-page-heading :title="__('Material Request')" :subtitle="__('Daftar Permintaan Alkes dan Obat')" />

    <a href="{{ route('main.material-request.create') }}" class="btn btn-accent">Buat permintaan ke unit</a>



    <div class="overflow-x-auto">
        <div class="py-2 border rounded-2xl my-5">
            <table class="table table-s dark:text-zinc-300">
                <thead>
                    <tr class="dark:text-zinc-300 ">
                        <th></th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Ke Unit</th>
                        <th>Status</th>
                        <th>Permintaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matreq as $idx => $request)
                        <flux:modal :name="'show-items-' . $request->id" class="w-full">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Data Barang Permintaan</flux:heading>
                                    <div class="flex gap-2">
                                        <flux:text class="mt-2">{{ $request->matreq_no }}</flux:text>
                                        <flux:text class="mt-2 text-accent">Untuk {{ $request->toUnit->nama }}</flux:text>

                                    </div>
                                </div>
                                <div>
                                    @foreach ($request->items as $item)
                                        <div wire:key="item-{{ $item->id }}">
                                            <div class="flex">
                                                <flux:text class="mt-2 mr-2">{{ $loop->iteration }}.</flux:text>
                                                <flux:text class="mt-2 mr-2">
                                                    {{ $item->farmalkes->nama }}
                                                </flux:text>
                                            </div>
                                            <div class="join">
                                                <flux:text class="join-item">Diminta: </flux:text>
                                                <input type="number" class="border-none focus:ring-0 broder-b-1"
                                                    value="{{ $item->pesan }}" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex">
                                    <flux:spacer />
                                    <flux:button type="submit" variant="primary">Save changes</flux:button>
                                </div>
                            </div>
                        </flux:modal>
                        <tr class="hover:bg-gray-200 dark:hover:bg-zinc-700">
                            <td>
                                {{ $loop->iteration }}
                            </td>
                            <td>
                                {{ $request->created_at->format('d-m-Y') }}
                            </td>
                            <td>
                                {{ $request->matreq_no }}
                            </td>
                            <td>
                                {{ $request->toUnit->nama }}
                            </td>
                            <td>
                                <div @class(["badge", "badge-accent" => $request->status == \App\Enums\MatreqStatus::REQUEST->value])>
                                    {{ \App\Enums\MatreqStatus::from($request->status)->label() ?? $request->status }}
                                </div>
                            </td>
                            <td>
                                <flux:modal.trigger :name="'show-items-' . $request->id">
                                    <flux:button>
                                        {{ $request->items()->count() }}
                                        Item
                                        <flux:icon.eye variant="mini" class="ms-2" />
                                    </flux:button>
                                </flux:modal.trigger>

                            </td>
                            <td>

                                <flux:icon.pencil-square class="hover:cursor-pointer hover:text-yellow-700" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="7" class="text-center">Tidak ada data</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="">
                {{ $matreq->links() }}
            </div>
        </div>
    </div>
</div>