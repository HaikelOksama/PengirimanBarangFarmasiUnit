<tr x-data="{
    edit: false,
    originalData: {
        nama: @entangle('nama'),
        kode: @entangle('kode'),
        satuan: @entangle('satuan'),
        kemasan: @entangle('kemasan'),
        hargaBeli: @entangle('harga_beli'),
        hna: @entangle('hna'),
        diskon: @entangle('diskon'),
        ppn: @entangle('ppn'),
    },
    startEdit() { this.edit = true },
    saveEdit() { $wire.save(); this.edit = false },
    cancelEdit() {
        $wire.nama = this.originalData.nama;
        $wire.kode = this.originalData.kode;
        $wire.satuan = this.originalData.satuan;
        $wire.kemasan = this.originalData.kemasan;
        $wire.harga_beli = this.originalData.hargaBeli;
        $wire.hna = this.originalData.hna;
        $wire.diskon = this.originalData.diskon;
        $wire.ppn = this.originalData.ppn;
        this.edit = false;
    }
}" class="hover:bg-gray-200 dark:hover:bg-zinc-700">
    <td>{{ $loop }}</td>

    <!-- Nama -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="text" wire:model.defer="nama" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center text-sm">
            {{ $farmalkes->nama }}
        </div>
    </td>

    <!-- Kode -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="text" wire:model.defer="kode" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->kode }}
        </div>
    </td>

    <!-- Satuan -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="text" wire:model.defer="satuan" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->satuan }}
        </div>
    </td>

    <!-- Kemasan -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="text" wire:model.defer="kemasan" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->kemasan }}
        </div>
    </td>

    <!-- PBF (just display, not editable) -->
    <td>
        {{ $farmalkes->pbf->nama }}
    </td>

    <!-- Harga Beli -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="number" wire:model.defer="harga_beli" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->harga_beli }}
        </div>
    </td>

    <!-- HNA -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="number" wire:model.defer="hna" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->hna }}
        </div>
    </td>

    <!-- Diskon -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="number" wire:model.defer="diskon" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->diskon }}
        </div>
    </td>

    <!-- PPN -->
    <td>
        <div x-show="edit" class="flex gap-2">
            <flux:input size="xs" type="number" wire:model.defer="ppn" class="input-sm" />
        </div>
        <div x-show="!edit" class="flex items-center">
            {{ $farmalkes->ppn }}
        </div>
    </td>

    <!-- Actions -->
    <td class="flex gap-2">
        <flux:icon.pencil-square class="hover:text-amber-500 hover:cursor-pointer" x-on:click="startEdit()"
            x-show="!edit" />
        <div x-show="edit" class="flex gap-1">
            <flux:icon.save class="hover:text-green-500 hover:cursor-pointer" x-on:click="saveEdit()" />
            <flux:icon.x class="hover:text-red-500 hover:cursor-pointer" x-on:click="cancelEdit()" />
        </div>
    </td>
</tr>