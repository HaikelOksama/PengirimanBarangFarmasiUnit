<div style="max-width: 800px; margin-left: auto; margin-right: auto; " class="bg-white h-full">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="text-start text-sm">
            <div class="flex">
                <img src="{{ asset('images/nusalima_logo_bg.png') }}" alt="" width="100px"
                    class="object-contain object-top">
                <div class="flex flex-col">
                    <h1 class="font-bold uppercase" style="font-size: 15px;"> <i>{{ $retur->matreq->fromUnit->nama }}</i></h1>
                    <p class="text-xs font-extralight">{{ $retur->matreq->fromUnit->alamat }}</p>

                    <h2 class="text-sm my-2 py-5 px-0 nowrap">RETUR BARANG KIRIMAN</h2>
                </div>
            </div>
            <i class="text-xs">Harap diterima Pengembalian Barang-barang Farmalkes sebagai berikut</i>
        </div>
        <div class="flex flex-col text-end w-75 justify-self-end" style="font-size: 11px;">
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>No. Pengiriman</i> </p>
                <strong class=" uppercase">{{ $retur->matreq->kirim_no }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Kepada</i> </p>
                <strong class=" uppercase">{{ $retur->matreq->toUnit->nama }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Keterangan</i> </p>
                <strong class=" uppercase">{{ $retur->keterangan }}</strong>
            </div>
        </div>

    </div>
    <table class="w-full text-xs border my-4 table-xs"
        style="font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
        <thead>
            <tr class="border border-s-0 border-e-0 border-black text-left">
                <th class="border-0 text-left">No.</th>
                <th>Nama</th>
                <th>Satuan</th>
                <th>Diretur</th>
            </tr>
        </thead>
        <tbody>
            @forelse($retur->items as $item)
                <tr>
                    <td class="border-0 text-center">{{ $loop->iteration }}</td>
                    <td class="border-0 text-left">{{ $item->farmalkes->nama }}</td>
                    <td class="border-0 text-left">{{ $item->farmalkes->satuan }}</td>
                    <td class="border-0 text-left">{{ $item->qty }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="border-0 text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</div>
