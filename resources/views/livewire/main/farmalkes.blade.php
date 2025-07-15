<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <x-page-heading :title="__('Barang Farmalkes')" :subtitle="__('Daftar Barang Farmalkes')" />

    <div class="overflow-x-scroll">
        <div class="py-2 border rounded-2xl my-5 overflow-x-auto">
            <div class="flex px-5 border-b-1 pb-2 gap-5">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend dark:text-slate-100">Pencarian</legend>
                    <label class="input">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input wire:model.live="search" type="search" class="grow" placeholder="Search" />
                        <kbd class="kbd kbd-sm">⌘</kbd>
                        <kbd class="kbd kbd-sm">K</kbd>
                    </label>
                </fieldset>
            </div>
            <table class="table table-sm dark:text-zinc-300">
                <thead>
                    <tr class="dark:text-zinc-300 ">
                        <th></th>
                        <th>Nama</th>
                        <th>Kode</th>
                        <th>Satuan</th>
                        <th>Kemasan</th>
                        <th>Pbf</th>
                        <th>Harga Beli</th>
                        <th>HNA</th>
                        <th>Diskon</th>
                        <th>PPN</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->farmalkes as $idx => $farm)
                        <livewire:component.farmalkes-item :loop="$loop->iteration" :farmalkes="$farm" :key="$farm->id . 'farmalkes-row'" />
                    @empty
                        <tr>
                            <th colspan="7" class="text-center">Tidak ada data</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-5">
                {{ $this->farmalkes->links() }}
            </div>
        </div>
    </div>
</div>