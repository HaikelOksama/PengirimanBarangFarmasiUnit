<div>
    <x-page-heading :title="__('Obat Expired')" :subtitle="__('Buat Obat Expired')" />
    <div class="flex flex-col gap-3">
        <div class="flex justify-between">
            <div class="flex-1/3 space-y-2">
                <div class="flex gap-5 mb-2">
                    <flux:input type="date" label="Tanggal"></flux:input> 
                    <flux:input label="Kirim Ke" readonly disabled value="Klinik Utama Nusa Lima"></flux:input>  
                </div>
                <flux:textarea label="Keterangan" wire:model="keterangan" rows="1"></flux:textarea>
                <flux:label>Obat Expired</flux:label>
                <div class="join w-full">
                    <div x-data="{ open: false, search: @entangle('searchFarmalkes'), selected: @entangle('selected') }"
                        class="relative w-1/2">
                        <input type="search" wire:model.live="searchFarmalkes" @focus="open = true" @click.away="open = false"
                            @class(["join-item w-full border rounded-lg px-4 py-2"]) placeholder="Search..." />
            
                        <ul x-show="open && search.length >= 2"
                            class="absolute z-999 bg-white dark:bg-amber-900 w-full mt-1 border rounded-lg max-h-60 overflow-y-auto">
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
                        <button class="btn join-item btn-accent mt-2" wire:click="addFarmalkes"
                            x-bind:disabled="!selected">Tambah</button>
                    </div>
                </div>
            </div>
            
                
            </div>
        </div>
       
        <div class="overflow-x-auto">
            <div class="py-2 border rounded-2xl my-5">
                <table class="table table-s ">
                    <thead>
                        <tr class="dark:text-zinc-300 ">
                            <th></th>
                            <th>Nama</th>
                            <th>PBF</th>
                            <th>Isi</th>
                            <th>Kemasan</th>
                            <th>Satuan</th>
                            <th>HNA</th>
                            <th>Jumlah</th>
                            <th>Expire Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($requestList as $idx => $request)
                            <tr class="hover:bg-gray-200 dark:hover:bg-zinc-700">
                                <td>
                                    <span class="flex items-center gap-2">
                                        {{ $loop->iteration }}
                                        {{-- delete icon --}}
                                        <flux:icon.x-circle class="cursor-pointer text-red-400" variant="mini"
                                            wire:click="unsetFarmalkes({{ $idx }})" title="Hapus" />
                                    </span>

                                </td>
                                <td>{{ $request['data']->nama }}</td>
                                <td>{{ $request['data']->pbf->nama }}</td>
                                <td>{{ $request['data']->isi }}</td>
                                <td>{{ $request['data']->kemasan }}</td>
                                <td>{{ $request['data']->satuan }}</td>
                                <td>Rp. {{ $request['data']->hna }}</td>
                                <td>
                                    <flux:input type="number" size="xs"
                                        wire:model="requestList.{{ $idx }}.qty" />
                                </td>
                                <td>
                                    <flux:input type="date" size="xs"
                                        wire:model="requestList.{{ $idx }}.expire_date"/>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="8" class="text-center">Tidak ada data</th>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <flux:error name="requestList" />
            <button class="btn btn-primary btn-outline border" wire:click="submit"
                wire:confirm="Buat Obat ED ?">Kirim Obat Ed</button>
        </div>  

    </div>
</div>
