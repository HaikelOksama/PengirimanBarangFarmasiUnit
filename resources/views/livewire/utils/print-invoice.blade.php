<div style="max-width: 800px; margin-left: auto; margin-right: auto; " class="bg-white h-full">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="text-start text-sm">
            <div class="flex">
                <img src="{{ asset('images/nusalima_logo_bg.png') }}" alt="" width="100px"
                    class="object-contain object-top">
                <div class="flex flex-col">
                    <h1 class="font-bold uppercase" style="font-size: 15px;"> <i>{{ $matreq->toUnit->nama }}</i></h1>
                    <p class="text-xs font-extralight">{{ $matreq->toUnit->alamat }}</p>

                    <h2 class="text-sm my-2 p-5">KIRIMAN GUDANG KE UNIT</h2>
                </div>
            </div>
            <i class="text-xs">Harap diterima Barang-barang Farmalkes sebagai berikut</i>
        </div>
        <div class="flex flex-col-reverse text-end w-75 justify-self-end" style="font-size: 11px;">
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Kepada</i> </p>
                <strong class=" uppercase">{{ $matreq->fromUnit->nama }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Dari</i> </p>
                <strong class=" uppercase">{{ $matreq->toUnit->nama }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Tanggal</i> </p>
                <strong
                    class=" uppercase">{{ \Carbon\Carbon::parse($matreq->tgl_kirim)->isoFormat('dddd, DD MMMM Y') }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>No. Matreq</i> </p>
                <strong class=" uppercase">{{ $matreq->matreq_no }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>No. Kiriman</i> </p>
                <strong class=" uppercase">{{ $matreq->kirim_no }}</strong>
            </div>

        </div>

    </div>

    <table class="w-full text-xs border-collapse my-4 table-xs"
        style="font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
        <thead>
            <tr class="border border-s-0 border-e-0 border-black text-left">
                <th class="border-0">No.</th>
                <th>Pesan</th>
                <th>Kirim</th>
                <th>Satuan</th>
                <th>ID</th>
                <th>Deskripsi</th>
                <th>Kemasan</th>
                <th>Isi</th>
                <th>Harga Satuan <br> (HNA)</th>
                <th>Disc</th>
                <th>Jml. Harga <br> (HNA * Kirim)</th>
                <th>Jml. Harga <br> (HNA - Disc * Kirim)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matreq->items as $item)
                <tr class="border border-black border-s-0 border-e-0 font-extralight  text-left">
                    <td style="width: 1%;">{{ $loop->iteration }}</td>
                    <td style="width: 1%;">{{ $item->pesan }}</td>
                    <td style="width: 1%;">{{ $item->kirim }}</td>
                    <td>{{ $item->farmalkes->satuan }}</td>
                    <td>{{ $item->farmalkes->kode }}</td>
                    <td style="width: 35%;" nowrap class="text-left">{{ $item->farmalkes->nama }}</td>
                    <td>{{ $item->farmalkes->kemasan }}</td>
                    <td>{{ $item->farmalkes->isi }}</td>
                    <td class="text-right">{{ number_format($item->hna, 2, ',', '.') }}</td>
                    <td>{{ number_format($item->diskon, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->subtotal_harga, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->total_harga, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="8">
                </th>
                <th>
                    Total
                </th>
                <th></th>
                <th>
                    {{ number_format($matreq->calculateSubtotal(), 2, ',', '.') }}
                </th>
                <th>
                    {{ number_format($matreq->calculateTotal(), 2, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>

    <div class="flex justify-between text-xs mt-8 flex-wrap gap-y-10">
        <div class="text-center w-1/3">
            <p class="font-bold">Apoteker Pengirim</p>
            <br><br>
            <p class="underline">{{ $matreq->toUnit->apoteker }}</p>
            <p>Apoteker</p>
        </div>
        <div class="text-center w-1/3">
            @if ($matreq->toUnit->type != 'rs')
                <p class="font-bold">Diperiksa oleh</p>
                <br><br>
                <p class="underline">dr. Tommy Kirana, MM.</p>
                <p>Manajer Klinik</p>

            @endif

        </div>
        <div class="text-center w-1/3">
            <p class="font-bold">Apoteker Penerima</p>
            <br><br>
            <p class="underline">{{ $matreq->fromUnit->apoteker }}</p>
            <p>Apoteker</p>
        </div>
        <div class="text-center w-1/3">
            <p class="font-bold">Mengetahui Kepala {{  Str::title($matreq->toUnit->nama) }}</p>
            <br><br>
            <p class="underline">{{ $matreq->toUnit->kepala_unit  }}</p>
            @if ($matreq->toUnit->type == 'rs')
                <p>Kepala Rumah Sakit</p>
            @else
                <p>Kepala Klinik</p>
            @endif

        </div>
        <div class="text-center w-1/3">
            <p class="font-bold">Mengetahui Kepala {{ Str::title($matreq->fromUnit->nama) }}</p>
            <br><br>
            <p class="underline">{{ $matreq->fromUnit->kepala_unit}}</p>
            <p>Kepala Klinik</p>
        </div>
    </div>
</div>