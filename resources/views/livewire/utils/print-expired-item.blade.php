<div style="max-width: 800px; margin-left: auto; margin-right: auto; " class="bg-white h-full">
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class="grid grid-cols-2 gap-5">
        <div class="text-start text-sm">
            <div class="flex gap-3">
                <img src="{{ asset('images/nusalima_logo_bg.png') }}" alt="" width="100px"
                    class="object-contain object-top">
                <div class="flex flex-col">
                    <h1 class="font-bold uppercase" style="font-size: 15px;"> <i>{{$ed->unit->nama}}</i></h1>
                    <p class="text-xs font-extralight">{{ $ed->unit->alamat }}</p>

                    <h2 class="text-sm my-2 p-5">Barang Expired</h2>
                </div>
            </div>
            <i class="text-xs">Telah dilakukan pemeriksaan terhadap barang-barang yang expired</i>
        </div>
        <div class="flex flex-col-reverse text-end w-75 justify-self-end" style="font-size: 11px;">
            {{-- <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Kepada</i> </p>
                <strong class=" uppercase">Klinik Utama Nusa Lima</strong>
            </div> --}}
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Dari</i> </p>
                <strong class=" uppercase">{{ $ed->unit->nama }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Keterangan</i> </p>
                <strong class=" uppercase">{{ $ed->keterangan }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Tanggal</i> </p>
                <strong
                    class=" uppercase">{{ \Carbon\Carbon::parse($ed->tanggal)->isoFormat('dddd, DD MMMM Y') }}</strong>
            </div>
            <div class="flex justify-between border-b-1 border-black">
                <p class=""><i>Nomor</i> </p>
                <strong class=" uppercase">{{ $ed->nomor }}</strong>
            </div>
        </div>

    </div>

    <table class="w-full text-xs border-collapse my-4 table-xs"
        style="font-size: 10px; font-family: Arial, Helvetica, sans-serif;">
        <thead>
            <tr class="border border-s-0 border-e-0 border-black text-left">
                <th class="border-0">No.</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Satuan</th>
                <th>Tanggal ED</th>
                <th>Jumlah ED</th>
                <th>Harga Satuan <br> (HNA)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ed->items as $item)
                <tr class="border border-black border-s-0 border-e-0 font-extralight  text-left">
                    <td style="width: 1%;">{{ $loop->iteration }}</td>
                    <td style="width: 1%;">{{ $item->farmalkes->kode }}</td>
                    <td style="width: 35%;" nowrap class="text-left">{{ $item->farmalkes->nama }}</td>
                    <td style="width: 1%;">{{ $item->farmalkes->satuan }}</td>
                    <td style="width: 1%;">{{ date('d-m-Y', strtotime($item->expire_date)) }}</td>
                    <td>{{ $item->qty }}</td>
                    <td class="text-right">{{ number_format($item->farmalkes->hna, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->farmalkes->hna * $item->qty, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        
    </table>

    <div class="flex justify-between text-xs mt-8 flex-wrap gap-y-10">
        <div class="text-center w-1/3">
            <p class="font-bold">Apoteker</p>
            <br><br>
            <p class="underline mt-4">{{$ed->unit->apoteker}}</p>
        </div>
        <div class="text-center w-1/3">
            <p class="font-bold">Mengetahui</p>
            <br><br>
            <p class="underline mt-4">{{$ed->unit->kepala_unit}}</p>
        </div>
    </div>
</div>