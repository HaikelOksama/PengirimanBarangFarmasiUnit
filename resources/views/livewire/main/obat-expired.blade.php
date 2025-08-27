<div>
    <x-page-heading :title="__('Obat Expired')" :subtitle="__('Daftar Obat Expired')" />

    <a href="{{ route('main.obat-expired.create') }}" wire:navigate class="btn btn-accent">Buat obat expired baru</a>

    <div class="overflow-x-auto">
        <div class="py-2 border rounded-2xl my-5">
            <div class="flex px-5 border-b-1 pb-2 gap-5 flex-wrap">
                <fieldset class="fieldset ">
                    <legend class="fieldset-legend dark:text-slate-100">Pencarian</legend>
                    <label class="input">
                        <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none"
                                stroke="currentColor">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </g>
                        </svg>
                        <input wire:model.live="searchQ" type="search" class="grow" placeholder="Search" />
                        <kbd class="kbd kbd-sm">⌘</kbd>
                        <kbd class="kbd kbd-sm">K</kbd>
                    </label>
                </fieldset>
                <fieldset class="fieldset ">
                    <legend class="fieldset-legend dark:text-slate-100">Tanggal Awal</legend>
                    <label class="input">
                        <input wire:model.live="startDateQ" type="date" class="grow" />
                    </label>
                </fieldset>
                <fieldset class="fieldset ">
                    <legend class="fieldset-legend dark:text-slate-100">Tanggal Akhir</legend>
                    <label class="input">
                        <input wire:model.live="endDateQ" type="date" class="grow" />
                    </label>
                </fieldset>
                <button class="btn btn-accent self-end mb-1" wire:click="reload">Cari</button>
                <div class="self-end mb-3 justify-self-center" wire:loading wire:target="reload">
                    <flux:icon.loading></flux:icon.loading>
                </div>
            </div>
            <table class="table table-s dark:text-zinc-300">
                <thead>
                    <tr class="dark:text-zinc-300 ">
                        <th></th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->expiredList as $item)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$item->tanggal}}</td>
                            <td>{{$item->nomor}}</td>
                            <td>
                                <div @class([
                                "badge badge-soft",
                                "badge-success" => $item->status == 'diterima',
                                "badge-error" => $item->status == 'ditolak',
                                "badge-warning" => $item->status == 'pending',

                                ])>{{ Str::ucfirst($item->status)}}
                                </div>
                                
                            </td>
                            <td>{{$item->keterangan}}</td>
                            <td class="flex gap-2">
                                <livewire:component.obat-expired-detail-modal :ed="$item" wire:key="expired-list-{{ $item->id }}" />
                                <flux:link target="_blank" href="{{ route('main.obat-expired.print', $item) }}"><flux:button icon="printer"></flux:button></flux:link>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Belum ada data</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="flex justify-end w-full">
                                {{ $this->expiredList->links() }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
