<div>
    <flux:modal.trigger :name="'show-items-' . $matreq->id">
        <flux:button>
            {{ $matreq->items()->count() }}
            Item
            <flux:icon.eye variant="mini" class="ms-2" />
        </flux:button>
    </flux:modal.trigger>
    <flux:modal size="xl" :name="'show-items-' . $matreq->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Data Barang Permintaan</flux:heading>
                <div class="flex gap-2">
                    <flux:text class="mt-2">{{ $matreq->matreq_no }}</flux:text>
                    <flux:text class="mt-2 text-amber-700 dark:text-accent">Untuk {{ $matreq->toUnit->nama }}
                    </flux:text>

                </div>

                @if($matreq->status == \App\Enums\MatreqStatus::KIRIM->value)
                    <div class="badge badge-success">
                        <svg class="size-[1em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g fill="currentColor" stroke-linejoin="miter" stroke-linecap="butt">
                                <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-linecap="square"
                                    stroke-miterlimit="10" stroke-width="2"></circle>
                                <polyline points="7 13 10 16 17 8" fill="none" stroke="currentColor" stroke-linecap="square"
                                    stroke-miterlimit="10" stroke-width="2"></polyline>
                            </g>
                        </svg>
                        <flux:text class="text-accent">Dikirim</flux:text>
                    </div>

                @endif
            </div>
            <div>
                @foreach ($matreq->items as $item)
                    @if($matreq->status == \App\Enums\MatreqStatus::REQUEST->value)
                        <flux:modal name="edit-farmalkes-{{ $item->id }}-{{ $matreq->id }}" class="md:w-100"
                            style="min-width: 40% !important; min-height: 400px !important; " :key="'modal-edit' . $item->id">
                            <div class="space-y-6">
                                <div>
                                    <flux:heading size="lg">Ganti Obat untuk Pengiriman</flux:heading>
                                    <div class="flex align-bottom">
                                        <flux:text class="mt-2">{{ $item->farmalkes->nama }} ({{ $item->farmalkes->pbf->nama }})
                                        </flux:text>
                                        <flux:icon.arrow-down-right variant="mini" />
                                    </div>
                                    <flux:spacer />
                                </div>
                                <flux:text class=" text-amber-600">Ganti Menjadi</flux:text>
                                <div class="join w-full">
                                    <div x-data="{ open: false, search: @entangle('searchFarmalkes'), selected: @entangle('selected') }"
                                        class="relative w-full">
                                        <input wire:model.live="searchFarmalkes" @focus="open = true" @keydown="open = true"
                                            @keydown.backspace="$wire.selected = null" @click.away="open = false"
                                            @class(["join-item w-full border rounded-lg px-4 py-2"])
                                            x-bind:class="selected ? 'border-green-500' : ''" placeholder="Search..." />

                                        <ul x-show="open && search.length >= 2"
                                            class="absolute  bg-white dark:bg-amber-900 w-full mt-1 border rounded-lg max-h-60 overflow-y-auto"
                                            style="z-index: 999999999999;">
                                            @forelse ($options as $opt)
                                                <li @click="selected = '{{ $opt->kode }}'; search = '{{ $opt->nama }}'; open = false"
                                                    class="px-4 py-2 hover:bg-blue-100 dark:hover:bg-amber-800 cursor-pointer"
                                                    wire:key="option-{{ $opt->kode }}">
                                                    {{ $opt->nama }} <--> {{ $opt->pbf->nama ?? $opt->pbf_kode }}
                                                </li>
                                            @empty
                                                <li class="px-4 py-2 text-gray-500">No results found.</li>
                                            @endforelse
                                                <li wire:loading wire:target='searchFarmalkes' class="px-4 py-2 text-green-500 fw-bold">Mengambil data... <flux:icon.loading /></li>
                                        </ul>
                                        <button class="btn join-item btn-accent mt-4"
                                            wire:click="changeFarmalkes({{ $item->farmalkes->id }})"
                                            x-bind:disabled="!selected">Ganti</button>

                                    </div>

                                </div>

                                <div class="flex">
                                    <flux:spacer />


                                </div>
                            </div>
                        </flux:modal>
                    @endif
                    <div wire:key="item-{{ $item->id }}" class="p-2"
                        style="box-shadow: rgba(14, 63, 126, 0.04) 0px 0px 0px 1px, rgba(42, 51, 69, 0.04) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.04) 0px 6px 6px -3px, rgba(14, 63, 126, 0.04) 0px 12px 12px -6px, rgba(14, 63, 126, 0.04) 0px 24px 24px -12px;">
                        <div class="flex flex-col">
                            <flux:text class="mt-2 mr-2 font-bold">
                                <div class="flex justify-between">
                                    <span class="text-amber-700 dark:text-accent">{{ $loop->iteration }}.
                                        {{ $item->farmalkes->nama }}</span>
                                    @if($matreq->status == \App\Enums\MatreqStatus::REQUEST->value)
                                        <div class="flex">
                                            @if($matreq->items->count() > 1)
                                            <flux:icon.trash class="hover:text-red-700 hover:cursor-pointer" variant="mini"
                                                wire:confirm="Hapus Obat {{ $item->farmalkes->nama }} dari Kiriman ?"
                                                wire:click="removeItem({{ $item->id }})">
                                            </flux:icon.trash>
                                            @endif
                                            <flux:modal.trigger name="edit-farmalkes-{{ $item->id }}-{{ $matreq->id }}">
                                                <flux:icon.pencil-square class="hover:text-yellow-700 hover:cursor-pointer"
                                                    variant="mini"></flux:icon.pencil-square>
                                            </flux:modal.trigger>
                                        </div>
                                    @endif
                                </div>

                                <br>
                                <span class="font-extralight">{{ $item->farmalkes->pbf->nama }}</span>
                            </flux:text>
                            @unlessrole('admin')
                            <flux:text>HNA : Rp.{{ $item->hna }}</flux:text>
                            @endunlessrole
                            @role('admin')
                            <div class="join border-b-2">
                                <flux:text class="join-item">HNA: </flux:text>
                                <input type='number' wire:model='items.{{ $item->id }}.hna'
                                    class="border-none focus:ring-0 broder-b-1 focus:bg-amber-100 dark:focus:bg-amber-800 join-item ms-2 text-sm px-5 w-80" />

                            </div>
                            @endrole
                            @role('admin')
                            <div class="join border-b-2">
                                <flux:text class="join-item">Diskon %: </flux:text>
                                <input type='number' wire:model='items.{{ $item->id }}.diskon'
                                    class="border-none focus:ring-0 broder-b-1 focus:bg-amber-100 dark:focus:bg-amber-800 join-item ms-2 text-sm px-5 w-80" />

                            </div>
                            @endrole
                        </div>
                        <div class="flex flex-col">
                            <div class="join border-b-2 ">
                                <flux:text class="join-item">Diminta: </flux:text>
                                <input @unlessrole('admin') @disabled($item->matreq->fromUnit != auth()->user()->unit || $matreq->status != \App\Enums\MatreqStatus::REQUEST->value) @endunlessrole type="number"
                                    wire:model="items.{{ $item->id }}.pesan"
                                    class="border-none focus:ring-0 broder-b-1 focus:bg-amber-100 dark:focus:bg-amber-800 join-item ms-2 text-sm px-5 w-full" />
                                <flux:text class="join-item font-extrabold ms-auto">{{ $item->farmalkes->satuan }}
                                </flux:text><br>
                            </div>


                            <flux:spacer />
                            <div class="join border-b-2">
                                <flux:text class="join-item">Dikirim: </flux:text>
                                <input @unlessrole('admin') @disabled($item->matreq->toUnit != auth()->user()->unit || $matreq->status != \App\Enums\MatreqStatus::REQUEST->value) @endunlessrole type="number"
                                    wire:model="items.{{ $item->id }}.kirim"
                                    class="border-none focus:ring-0 broder-b-1 dark:focus:bg-amber-800 join-item ms-2 text-sm w-full px-5" />
                                <flux:text class="join-item font-extrabold ms-auto">{{ $item->farmalkes->satuan }}
                                </flux:text>
                            </div>
                            <flux:text class="font-extrabold">Subtotal : Rp.{{ $item->subtotal_harga }}</flux:text>
                            <flux:text class="font-extrabold">Total : Rp.{{ $item->total_harga }}</flux:text>
                        </div>


                    </div>
                @endforeach
            </div>
            @if(Auth::user()->hasRole('admin') || $matreq->status == \App\Enums\MatreqStatus::REQUEST->value)
           
                <div class="flex">
                    <flux:spacer />
                    <flux:button.group>
                        <flux:modal.trigger name="add-farmalkes-{{ $matreq->id }}">
                            <flux:button icon="plus">Tambah</flux:button>
                        </flux:modal.trigger>
                        <flux:button icon="bolt" type="submit" variant="filled" wire:click="save">Simpan Perubahan
                        </flux:button>
                        @if(auth()->user()->unit == $matreq->toUnit)
                            <flux:button icon="check" type="submit" variant="primary" class=" text-slate-900 dark:bg-green-200"
                                wire:confirm="Kirim Permintaan no {{ $matreq->matreq_no }} dari {{ $matreq->fromUnit->nama }} ?"
                                wire:click="send">
                                Kirim Permintaan
                            </flux:button>
                        @endif
                    </flux:button.group>
                </div>
            @endif
        </div>
    </flux:modal>
    <flux:modal name="add-farmalkes-{{ $matreq->id }}" class="md:w-100"
        style="min-width: 40% !important; min-height: 400px !important; " :key="'modal-edit' . $item->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Tambah Item</flux:heading>
                <div class="flex align-bottom">
                    <flux:text class="mt-2">Untuk {{ $matreq->matreq_no }}
                    </flux:text>
                    <flux:icon.arrow-down-right variant="mini" />
                </div>
                <flux:spacer />
            </div>
            <flux:text class=" text-amber-600">Pilih Obat/Alkes Baru</flux:text>
            <div class="join w-full">
                <div x-data="{ open: false, search: @entangle('searchFarmalkes'), selected: @entangle('selected') }"
                    class="relative w-full">
                    <input wire:model.live="searchFarmalkes" @focus="open = true" @keydown="open = true"
                        @keydown.backspace="$wire.selected = null" @click.away="open = false"
                        @class(["join-item w-full border rounded-lg px-4 py-2"])
                        x-bind:class="selected ? 'border-green-500' : ''" placeholder="Search..." />

                    <ul x-show="open && search.length >= 2"
                        class="absolute  bg-white dark:bg-amber-900 w-full mt-1 border rounded-lg max-h-60 overflow-y-auto"
                        style="z-index: 999999999999;">
                        @forelse ($options as $opt)
                            <li @click="selected = '{{ $opt->kode }}'; search = '{{ $opt->nama }}'; open = false"
                                class="px-4 py-2 hover:bg-blue-100 dark:hover:bg-amber-800 cursor-pointer"
                                wire:key="option-{{ $opt->kode }}">
                                {{ $opt->nama }} <--> {{ $opt->pbf->nama ?? $opt->pbf_kode }}
                            </li>
                        @empty
                            <li class="px-4 py-2 text-gray-500">No results found.</li>
                        @endforelse
                    </ul>
                    <button class="btn join-item btn-accent mt-4"
                        wire:click="addFarmalkes"
                        x-bind:disabled="!selected">Tambah</button>

                </div>

            </div>
        </div>
    </flux:modal>
</div>

@assets
<style>
    dialog {
        /* min-width: 80% !important; */
    }
</style>
@endassets