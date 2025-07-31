<div>
    {{-- The Master doesn't talk, he acts. --}}
    <x-page-heading :title="__('Material Request')" :subtitle="__('Daftar Permintaan Alkes dan Obat')" />

    @unlessrole('admin')
        <a href="{{ route('main.material-request.create') }}" class="btn btn-accent">Buat permintaan ke unit</a>
    @endunlessrole
    <div class="overflow-x-auto">
        <div class="py-2 border rounded-2xl my-5">
            <div class="flex px-5 border-b-1 pb-2 gap-5 flex-wrap">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend dark:text-slate-100">Unit</legend>
                    <select wire:model.live="unitQ" class="select  rounded-0">
                        <option value="" selected>--------</option>
                        @foreach ($this->units() as $key => $unit)
                            <option value="{{ $key }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </fieldset>
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
                <fieldset class="fieldset">
                    <legend class="fieldset-legend dark:text-slate-100">Tanggal Dari - Sampai</legend>
                    <div class="join" x-data="{ min: @entangle('startDate'), max: '{{ date('Y-m-d') }}' }">
                        <input type="date" wire:model.live="startDate" class="input join-item"
                            :max="{{ date('Y-m-d') }}">
                        <input x-bind:disabled="min == ''" type="date" wire:model.live="endDate"
                            class="input join-item" :min="min" :max="{{ date('Y-m-d') }}">
                    </div>

                </fieldset>
            </div>
            <table class="table table-s dark:text-zinc-300">
                <thead>
                    <tr class="dark:text-zinc-300 ">
                        <th></th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        @role('admin')
                            <th>Permintaan Dari Unit</th>
                        @endrole
                        <th>Ke Unit</th>
                        <th>Status</th>
                        <th>Permintaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matreq as $idx => $request)
                        <tr class="hover:bg-gray-200 dark:hover:bg-zinc-700" wire:key="matreq-{{ $request->id }}">
                            <td>
                                <div class="flex">
                                    {{ $loop->iteration }}
                                    @if ($request->status == \App\Enums\MatreqStatus::REQUEST->value)
                                        <span>
                                            <flux:icon.mail-question class="ms-2 text-red-300" />
                                        </span>
                                    @elseif($request->status == \App\Enums\MatreqStatus::KIRIM->value)
                                        <span>
                                            <flux:icon.truck class="ms-2 text-blue-300" />
                                        </span>
                                    @elseif($request->status == \App\Enums\MatreqStatus::SELESAI->value)
                                        <span>
                                            <flux:icon.check-circle class="ms-2 text-green-300" />
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ $request->created_at->format('d-m-Y') }}
                            </td>
                            <td>
                                {{ $request->matreq_no }}
                            </td>
                            @role('admin')
                                <td>
                                    {{ $request->fromUnit->nama }}
                                </td>
                            @endrole
                            <td>
                                {{ $request->toUnit->nama }}
                            </td>
                            <td>
                                <div @class([
                                    'badge',
                                    'badge-accent' =>
                                        $request->status == \App\Enums\MatreqStatus::REQUEST->value,
                                    'badge-info' => $request->status == \App\Enums\MatreqStatus::KIRIM->value,
                                    'badge-success' =>
                                        $request->status == \App\Enums\MatreqStatus::SELESAI->value,
                                ])>
                                    {{ \App\Enums\MatreqStatus::from($request->status)->label() ?? $request->status }}
                                </div>
                            </td>
                            <td>
                                <livewire:component.modal.matreq-modal lazy :matreq="$request" :key="'show-items-modal' . $request->id" />
                            </td>
                            <td>
                                <div class="flex">
                                    <flux:modal.trigger :name="'show-items-' . $request->id">
                                        <flux:icon.pencil-square class="hover:cursor-pointer hover:text-yellow-700" />
                                    </flux:modal.trigger>
                                    <flux:icon.printer
                                        x-on:click="function() { window.open('{{ route('main.material-request.print', $request) }}', '_blank') }"
                                        class="hover:cursor-pointer hover:text-red-600" />
                                    @if ($request->status == \App\Enums\MatreqStatus::REQUEST->value)
                                        <flux:icon.trash class="hover:cursor-pointer hover:text-red-700"
                                            wire:click="destroy({{ $request->id }})"
                                            wire:confirm="Hapus Permintaan {{ $request->matreq_no }} ?" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if ($request->status != \App\Enums\MatreqStatus::REQUEST->value)
                            <tr @class([
                                'hover:bg-gray-500 dark:hover:bg-zinc-700',
                                'bg-blue-200 dark:bg-amber-800' =>
                                    $request->status == \App\Enums\MatreqStatus::KIRIM->value,
                                'bg-green-200 dark:bg-green-800' =>
                                    $request->status == \App\Enums\MatreqStatus::SELESAI->value,
                            ])>
                                <td><flux:icon.corner-down-right /></td>
                                <td colspan="2">Dikirim Pada {{ $request->updated_at->format('d-m-Y H:i') }}</td>
                                <td colspan="2">Nomor Pengiriman : {{ $request->kirim_no }}</td>
                                <td colspan="2">
                                    <div class="join">
                                        @if ($request->status == \App\Enums\MatreqStatus::KIRIM->value)
                                            <button class="btn btn-sm btn-accent border rounded join-item"
                                                wire:confirm="Terima Kiriman {{ $request->matreq_no }} ?"
                                                wire:click="receive({{ $request->id }})">
                                                Terima Kiriman
                                            </button>
                                        @elseif ($request->status == \App\Enums\MatreqStatus::SELESAI->value)
                                            <flux:button disabled variant="filled" class="bg-green-600  join-item">Sudah
                                                Selesai</flux:button>
                                            @if ($request->fromUnit->id == auth()->user()->unit_id)
                                                <livewire:component.modal.retur-modal :matreq="$request"
                                                    :key="'show-retur-modal' . $request->id" />
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <th colspan="7" class="text-center">Tidak ada data</th>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-5">
                {{ $matreq->links() }}
            </div>
        </div>

    </div>


</div>
