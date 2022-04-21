@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="row">
        @foreach ($pemeriksaans as $item)
            @if(!$item->waktu_selesai)
            <div class="col-3">
                <div class="small-box bg-info" id="box-{{ $item->id }}">
                    <div class="inner">
                        <h3 id="time-{{ $item->id }}"></h3>
                        <p class="font-weight-bold m-0">{{ $item->penyakit->nama }}</p>
                        <p>{{ $item->pasien->nama }}</p>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <div class="row">
        <div class="col">
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="penyakit" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pasien</th>
                                <th>Penyakit</th>
                                <th>Status</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($isiTabel as $item)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $item->created_at->format('d M Y') }}</td>
                                    <td>{{ $item->pasien->nama }}</td>
                                    <td>{{ $item->penyakit->nama }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->created_at->format('H:i:s') }}</td>
                                    <td>{{ !is_null($item->waktu_selesai) ? date('H:i:s', strtotime($item->waktu_selesai)) : '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Belum ada pemeriksaan hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
        </div>
    </div>
@endsection

@push('style')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
@endpush

@push('script')
    <script>

        let pemeriksaan = {};

        @foreach ($pemeriksaans as $item)
            @if(!$item->waktu_selesai)

            pemeriksaan = {
                ...pemeriksaan,
                ...{
                    '{{ $item->id }}' : {
                        'id' : {{ $item->id }},
                        'created_at' : '{{$item->created_at->format('m/d/Y H:i:s')}}'
                    }
                }
            };
            @endif
        @endforeach

        setInterval(setTime, 1000);

        function setTime(){
            $.each(pemeriksaan, function(index, value) {

                let id = value['id'];

                //Waktu sekarang
                let currTime = new Date();

                //Waktu mulai
                let pastTime = new Date(value['created_at']);

                //Selisih waktu (dalam miliseconds)
                let diff = currTime - pastTime;

                //Konversi selisih(miliseconds) ke h(jam), m(menit), s(detik)
                let h = Math.floor(diff/(1000 * 60 * 60));
                diff -= h * (1000 * 60 * 60);

                let m = Math.floor(diff/(1000 * 60));
                diff -= m * (1000 * 60);

                let s = Math.floor(diff/1000);

                display(id, h, m, s);

            });
            reload();
        }

        function display(id, h, m, s) {
            var time = document.getElementById("time-" + id);

            var h = Number(h);
            var m = Number(m);
            var s = Number(s);

            var hDisplay = h > 0 ? (h<10 ? "0" + h + ":" : h + ":") : "00:";
            var mDisplay = m > 0 ? (m<10 ? "0" + m + ":" : m + ":") : "00:";
            var sDisplay = s > 0 ? (s<10 ? "0" + s : s ) : "00";

			if (h > 0) $("#box-" + id).removeClass("bg-info").addClass("bg-danger");

            time.innerHTML = hDisplay + mDisplay + sDisplay;
        }

        function reload(){
            let reload = localStorage.getItem('reload');
            localStorage.removeItem('reload');
            if(reload) window.location.reload();
        }

    </script>

<script src="{{ asset('template/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script>
    $(function() {
        $('#penyakit').DataTable();
    });
</script>
@endpush
