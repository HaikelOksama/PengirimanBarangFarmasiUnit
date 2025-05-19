<div>
    {{-- The Master doesn't talk, he acts. --}}
    <x-page-heading :title="__('Permintaan Masuk')" :subtitle="__('Daftar Permintaan Alkes dan Obat Masuk dari Unit')" />

    {{-- <a href="{{ route('main.material-request.create') }}" class="btn btn-accent">Buat permintaan ke unit</a> --}}

    <div class="overflow-x-auto">
        <div class="py-2 border rounded-2xl my-5">
            <div class="flex px-5 border-b-1 pb-2 gap-5">
                <fieldset class="fieldset">
                    <legend class="fieldset-legend dark:text-slate-100">Unit</legend>
                    <select wire:model.live="unitQ" class="select rounded-0">
                        <option value="" selected>--------</option>
                        @foreach ($this->units() as $key => $unit)
                            <option value="{{ $key }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </fieldset>
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
                        <input wire:model.live="searchQ" type="search" class="grow" placeholder="Search" />
                        <kbd class="kbd kbd-sm">⌘</kbd>
                        <kbd class="kbd kbd-sm">K</kbd>
                    </label>
                </fieldset>
                <fieldset class="fieldset">
                    <legend class="fieldset-legend dark:text-slate-100">Tanggal Dari - Sampai</legend>
                    <div class="join" x-data="{ min: @entangle('startDate'), max: '{{ date('Y-m-d') }}'  }">
                        <input type="date" wire:model.live="startDate" class="input join-item"
                            :max="{{ date('Y-m-d') }}">
                        <input x-bind:disabled="min == ''" type="date" wire:model.live="endDate" class="input join-item" :min="min" :max="{{ date('Y-m-d') }}">
                    </div>

                </fieldset>
            </div>
            <table class="table table-s dark:text-zinc-300">
                <thead>
                    <tr class="dark:text-zinc-300 ">
                        <th></th>
                        <th>Tanggal</th>
                        <th>Nomor</th>
                        <th>Permintaan Dari Unit</th>
                        @role('admin')
                        <th>Kiriman Unit</th>
                        @endrole
                        <th>Status</th>
                        <th>Permintaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matreq as $idx => $request)
                                        
                                        <tr class="hover:bg-gray-200 dark:hover:bg-zinc-700"  wire:key="matreq-{{ $request->id }}">
                                            <td>
                                                <div class="flex">
                                                    {{ $loop->iteration }}
                                                    @if($request->status == \App\Enums\MatreqStatus::REQUEST->value)
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
                                            <td>
                                                {{ $request->fromUnit->nama }}
                                            </td>
                                            @role('admin')
                                            <td>
                                                {{ $request->toUnit->nama }}
                                            </td>
                                            @endrole
                                            <td>
                                                <div @class([
                                                    "badge",
                                                    "badge-accent" => $request->status == \App\Enums\MatreqStatus::REQUEST->value,
                                                    'badge-info' => $request->status == \App\Enums\MatreqStatus::KIRIM->value,
                                                    "badge-success" => $request->status == \App\Enums\MatreqStatus::SELESAI->value,
                                                ])>
                                                    {{ \App\Enums\MatreqStatus::from($request->status)->label() ?? $request->status }}
                                                </div>
                                            </td>
                                            <td>
                                                <livewire:component.modal.matreq-modal lazy :matreq="$request" :key="'show-items-mat-modal' . $request->id" />
                                            </td>
                                            <td>
                                                <div class="flex gap-2">
                                                    <flux:modal.trigger :name="'show-items-' . $request->id">
                                                        <flux:icon.pencil-square class="hover:cursor-pointer hover:text-yellow-700" />
                                                    </flux:modal.trigger>
                                                    @if($request->status != \App\Enums\MatreqStatus::REQUEST->value)
                                                        <a href="{{ route('main.kirim.print', $request) }}" target="__blank">
                                                            <flux:icon.printer class="hover:cursor-pointer hover:text-red-600" />
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @if($request->status != \App\Enums\MatreqStatus::REQUEST->value)
                                                        <tr @class([
                                                            "hover:bg-gray-200 dark:hover:bg-zinc-700",
                                                            "bg-blue-200 dark:bg-amber-800" => $request->status == \App\Enums\MatreqStatus::KIRIM->value,
                                                            "bg-green-200 dark:bg-green-800" => $request->status == \App\Enums\MatreqStatus::SELESAI->value
                                                        ])>
                                                            <td><flux:icon.corner-down-right /></td>
                                                            <td colspan="2">Dikirim Pada {{ $request->tgl_kirim->format('d-m-Y H:i') }}</td>
                                                            <td colspan="2">Nomor Pengiriman : {{ $request->kirim_no }}</td>
                                                            <td colspan="2">
                                                                @if ($request->status == \App\Enums\MatreqStatus::SELESAI->value)
                                                                <button class="btn btn-success w-28 border rounded"  >
                                                                    Selesai
                                                                </button>
                                                                    {{-- <flux:button disabled variant="primary" class="bg-green-600">Sudah Selesai</flux:button> --}}
                                                                @endif
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

            <div class="">
                {{ $matreq->links() }}
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('info', () => {
        $wire.$refresh();
    })
</script>

@endscript