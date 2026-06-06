@extends('layouts.app')
@section('content')
@php
$bulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
@endphp
<div class="riwayat-page">
    <!-- FILTER -->
    <form
        method="GET"
        action="{{ route('riwayat') }}"
        class="filter-box">

        <!-- MODE -->
        {{-- <div class="filter-group" >
            <label>
                Mode Filterisasi
            </label>
            <select
                name="mode"
                onchange="this.form.submit()">
                <option value="harian"
                    {{ request('mode')=='harian'?'selected':'' }}>
                    Harian
                </option>
                <option value="bulanan"
                    {{ request('mode')=='bulanan'?'selected':'' }}>
                    Bulanan
                </option>
                <option value="tahunan"
                    {{ request('mode')=='tahunan'?'selected':'' }}>
                    Tahunan
                </option>
            </select>
        </div> --}}
        <!-- BULAN -->
        <div class="filter-group">
            <label>
                Bulan
            </label>
            <select name="bulan">
                <option value="">
                    -- Bulan --
                </option>
                @foreach($bulan as $i => $b)
                    <option
                        value="{{ $i }}"
                        {{ request('bulan')==$i?'selected':'' }}>
                        {{ $b }}
                    </option>
                @endforeach
            </select>
        </div>
         <div class="filter-group">
        </div>
        <!-- TANGGAL -->
        <div class="filter-group">
            <label>
                Dari
            </label>
            <input
                type="date"
                name="tanggal_mulai"
                value="{{ request('tanggal_mulai') }}">
        </div>

        <div class="filter-group">
            <label>
                Sampai
            </label>
            <input
                type="date"
                name="tanggal_selesai"
                value="{{ request('tanggal_selesai') }}">
        </div>
        <!-- ACTION -->
        <div class="filter-actions">
            <button
                type="submit"
                class="btn-filter">
                Filter
            </button>
            <a
                href="{{ route('riwayat') }}"
                class="btn-reset">
                Reset
            </a>
        </div>
    </form>
    <!-- CHART -->
    {{-- <div class="chart-card">
        <h3 class="chart-title">
            Grafik Keuntungan
            {{ $mode=='harian'?'Harian':($mode=='bulanan'?'Bulanan':'Tahunan') }}
        </h3>
        <canvas
            id="chartKeuntungan"
            class="chart-canvas"></canvas>
    </div> --}}
    <!-- BUTTON PRINT -->
    <button
        onclick="printSemua()"
        class="btn-print-stock">
        🖨️ Cetak Laporan
    </button>
    <!-- TABLE KEUNTUNGAN -->
    <div class="profit-card">
        <h3 class="section-title">
            Pendapatan per Bulan
        </h3>
        <table class="profit-table">
            <tr>
                <th>Bulan</th>
                <th>Total</th>
            </tr>
            @foreach($keuntungan as $k)
            <tr>
                <td>
                    {{ $bulan[$k->bulan] }}
                </td>
                <td>
                    Rp {{ number_format($k->total) }}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <!-- RIWAYAT -->
    <h2 class="section-title">
        Laporan Transaksi
    </h2>
    <div class="content-print">
        @foreach($transaksis as $t)
        <div
            id="transaksi-{{ $t->id_transaksi }}"
            class="transaksi-card">
            <p class="tanggal-transaksi">
                {{ $t->tanggal }}
            </p>
            {{-- <button
                onclick="printTransaksi({{ $t->id_transaksi }})"
                class="btn-print-single">
                Cetak
            </button> --}}
            <table class="transaksi-table">
                <tr>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
                @foreach($t->details as $d)
                <tr>
                    <td>
                        {{ $d->barang->nama ?? '-' }}
                    </td>
                    <td>
                        {{ $d->jumlah }}
                    </td>
                    <td>
                        Rp {{ number_format($d->subtotal) }}
                    </td>
                </tr>
                @endforeach
            </table>
            <h4 class="total-transaksi">
                Total = Rp {{ number_format($t->total) }}
            </h4>
        </div>
        @endforeach
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {
    const rawLabels = @json($labels ?? []);
    const data = @json($data ?? []);
    const mode = "{{ $mode ?? 'harian' }}";
    console.log("LABEL:", rawLabels);
    console.log("DATA:", data);
    let labels = [];
    if (mode === 'harian') {
        labels = rawLabels.map(t => {
            let d = new Date(t);
            return d.toLocaleDateString('id-ID',{day:'numeric',month:'short'});
        });
    }
    else if (mode === 'bulanan') {
        const bulan = ['', 'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        labels = rawLabels.map(b => bulan[b]);
    }
    else {
        labels = rawLabels;
    }
    if (labels.length === 0) {
        labels = ['Tidak ada data'];
    }
    if (data.length === 0) {
        data.push(0);
    }
    new Chart(document.getElementById('chartKeuntungan'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Keuntungan',
                data: data,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
});


</script>
