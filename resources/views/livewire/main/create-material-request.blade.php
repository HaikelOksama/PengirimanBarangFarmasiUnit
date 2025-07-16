<div>
    <x-page-heading :title="__('Buat Permintaan Alkes dan Obat')" :subtitle="__('Ajukan permintaan baru ke unit')" />

    <div class="flex gap-5">
        <div class="w-1/2 ">
            <flux:field>
                <flux:label badge="Required">Permintaan ke Unit</flux:label>
                <flux:select wire:model="toUnit" placeholder="Pilih Unit...">
                    <flux:select.option>-- Pilih Salah Satu --</flux:select.option>
                    @foreach ($this->units as $unit)
                        <flux:select.option value="{{ $unit->id }}">{{ $unit->nama }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="toUnit" />
            </flux:field>
        </div>
        <flux:field>
            <flux:label badge="Required">Tanggal</flux:label>
            <flux:input type='date' wire:model='tglBuat'></flux:input>
            <flux:error name="tglBuat" />
        </flux:field>
    </div>

    <div class="py-5 dark:text-zinc-300">
        <p>Obat & Alkes diminta</p>
        <div class="join w-full">
            <div x-data="{ open: false, search: @entangle('searchFarmalkes'), selected: @entangle('selected') }"
                class="relative w-1/4">
                <input type="search" wire:model.live="searchFarmalkes" @focus="open = true" @click.away="open = false"
                    @class(["join-item w-full border rounded-lg px-4 py-2"]) placeholder="Search..." />

                <ul x-show="open && search.length >= 2"
                    class="absolute z-999 bg-white dark:bg-amber-900 w-full mt-1 border rounded-lg max-h-60 overflow-y-auto">
                    {{-- @forelse ($options as $opt)
                        <li @click="selected = '{{ $opt->id }}'; search = '{{ $opt->nama }}'; open = false"
                            class="px-4 py-2 hover:bg-blue-100 dark:hover:bg-amber-800 cursor-pointer"
                            wire:key="option-{{ $opt->id }}">
                            {{ $opt->nama }} <--> {{ $opt->pbf->nama }}
                        </li>
                    @empty
                        <li class="px-4 py-2 text-gray-500">No results found.</li>
                    @endforelse --}}
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
                <button class="btn join-item btn-accent" wire:click="addFarmalkes"
                    x-bind:disabled="!selected">Tambah</button>
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
                            <th>Diminta</th>
                        </tr>
                    </thead>
                    <tbody>
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
                                    <input type="number" class="w-full border rounded-lg px-4 py-2 max-w-xs"
                                        wire:model="requestList.{{ $idx }}.qty">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <th colspan="7" class="text-center">Tidak ada data</th>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <flux:error name="requestList" />
            <button class="btn btn-primary btn-outline border" wire:click="submit"
                wire:confirm="Kirim permintaan farmalkes ?">Kirim Permintaan</button>
        </div>
    </div>
</div>