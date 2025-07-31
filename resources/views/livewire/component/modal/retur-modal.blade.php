<div>
    <flux:modal.trigger :name="'retur-items-' . $matreq->id">
        <button class="btn btn-secondary rounded">
            <flux:icon.arrow-right variant="mini"></flux:icon.arrow-right> Retur
        </button>
    </flux:modal.trigger>
    <flux:modal size="xl" class="min-w-4xl" :name="'retur-items-' . $matreq->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Retur Barang Diterima</flux:heading>
                <div class="flex gap-2">
                    <flux:text class="mt-2">{{ $matreq->matreq_no }}</flux:text>
                    <flux:text class="mt-2 text-amber-700 dark:text-accent">Untuk {{ $matreq->toUnit->nama }}
                    </flux:text>

                </div>
                <flux:separator />
                <flux:spacer />
                <flux:input size="sm" type="date" wire:model="tglRetur" label="Tanggal Retur"></flux:input>
            </div>
            <div class="flex max-h-96 overflow-y-scroll">
                <div>
                    <flux:text>Barang diterima</flux:text>
                    <flux:spacer />
                    @foreach ($matreq->items as $item)
                        <div wire:key="item-{{ $item->id }}" class="p-2 border rounded border-zinc-300"
                            style="box-shadow: rgba(14, 63, 126, 0.04) 0px 0px 0px 1px, rgba(42, 51, 69, 0.04) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.04) 0px 6px 6px -3px, rgba(14, 63, 126, 0.04) 0px 12px 12px -6px, rgba(14, 63, 126, 0.04) 0px 24px 24px -12px;">
                            <div class="flex flex-col">
                                <flux:text class="mt-2 mr-2 font-bold">
                                    <div class="flex justify-between">
                                        <span class="text-amber-700 dark:text-accent">{{ $loop->iteration }}.
                                            {{ $item->farmalkes->nama }}</span>
                                        <flux:icon.arrow-right
                                            class="hover:text-green-700 hover:cursor-pointer border rounded p-1"
                                            role="button"
                                            wire:click="addReturItem({{ $item->id }})"></flux:icon.arrow-right>
                                    </div>

                                    <br>
                                    <span class="font-extralight">{{ $item->farmalkes->pbf->nama }}</span>
                                </flux:text>
                            </div>
                            <div class="flex flex-col">
                                <div class="join border-b-2 ">
                                    <flux:text class="join-item">Diterima: </flux:text>
                                    <input type="number" disabled wire:model="items.{{ $item->id }}.pesan"
                                        class="border-none focus:ring-0 broder-b-1 focus:bg-amber-100 dark:focus:bg-amber-800 join-item ms-2 text-sm px-5 w-full" />
                                    <flux:text class="join-item font-extrabold ms-auto">{{ $item->farmalkes->satuan }}
                                    </flux:text><br>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex  flex-col ps-2 border-l min-w-1/2">
                    <flux:text>Barang diretur</flux:text>
                    @foreach ($returItems as $item)
                        <div wire:key="item-retur-{{ $item['item']->id }}"
                            class="p-2 dark:bg-amber-900 bg-amber-100 rounded border border-zinc-300"
                            style="box-shadow: rgba(14, 63, 126, 0.04) 0px 0px 0px 1px, rgba(42, 51, 69, 0.04) 0px 1px 1px -0.5px, rgba(42, 51, 70, 0.04) 0px 3px 3px -1.5px, rgba(42, 51, 70, 0.04) 0px 6px 6px -3px, rgba(14, 63, 126, 0.04) 0px 12px 12px -6px, rgba(14, 63, 126, 0.04) 0px 24px 24px -12px;">
                            <div class="flex flex-col">
                                <flux:text class="mt-2 mr-2 font-bold">
                                    <div class="flex justify-between">
                                        <span class="text-amber-700 dark:text-accent">{{ $loop->iteration }}.
                                            {{ $item['item']->farmalkes->nama }}</span>
                                        <flux:icon.trash
                                            class="hover:text-red-700 hover:cursor-pointer border rounded p-1"
                                            role="button" wire:click="removeReturItem({{ $item['item']->id }})">
                                        </flux:icon.trash>
                                    </div>

                                    <br>
                                    <span class="font-extralight">{{ $item['item']->farmalkes->pbf->nama }}</span>
                                </flux:text>
                            </div>
                            <div class="flex flex-col">
                                <div class="join border-b-2 ">
                                    <flux:text class="join-item">Diretur: </flux:text>
                                    <input min="1" type="number"
                                        wire:model="returItems.{{ $item['item']->id }}.qty"
                                        class="border-none focus:ring-0 broder-b-1 focus:bg-amber-100 dark:focus:bg-amber-800 join-item ms-2 text-sm px-5 w-full" />
                                    <flux:text class="join-item font-extrabold ms-auto">
                                        {{ $item['item']->farmalkes->satuan }}
                                    </flux:text><br>

                                </div>
                                @error('returItems.' . $item['item']->id . '.qty')
                                    <flux:text class="text-red-700">{{ $message }}</flux:text>
                                @enderror
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <flux:textarea label="Keterangan" wire:model="keterangan">

            </flux:textarea>
            <div class="join">
                <flux:button icon="check" type="submit" variant="primary" class=" join-item text-slate-900 dark:bg-green-200"
                wire:confirm="Kirim Retur ke {{ $matreq->fromUnit->nama }} ?" wire:click="submitRetur">
                Kirim retur barang
            </flux:button>
            {{-- <livewire:component.modal.retur-list-modal :matreq="$matreq" :key="'retur-list-base'.$matreq->id"/>  --}}
            </div>
          
        </div>
        
    </flux:modal>
</div>
