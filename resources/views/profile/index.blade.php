@extends('layouts.app')
@section('content')
@php
    $mode = request('mode', 'harian');
    $bulan = [
        1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April',
        5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus',
        9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'
    ];
@endphp
<div class="profile-page">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <h2>{{ Auth::user()->name }}</h2>
            <p>Admin</p>
        </div>
        @if(session('success'))
            <div class="profile-alert profile-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="profile-alert profile-error">
                {{ session('error') }}
            </div>
        @endif
        <div class="profile-info-box">
            <h3>Informasi Akun</h3>
            <div class="profile-info-item">
                <span>Nama</span>
                <strong>{{ Auth::user()->name }}</strong>
            </div>
            <div class="profile-info-item">
                <span>Email</span>
                <strong>{{ Auth::user()->email }}</strong>
            </div>
        </div>
        <div class="profile-form-box">
            <h3>Keamanan Akun</h3>
            <a href="{{ route('profile.email.form') }}" class="profile-btn-link">
                Ganti Email
            </a>
            <a href="{{ route('profile.password.form') }}" class="profile-btn-link">
                Ganti Password
            </a>
            <form method="POST" action="/logout" class="logout-form">
                @csrf
                <button
                    type="submit"
                    class="btn-logout"
                    onclick="return confirmLogout(this)"
                    style="color:red;">
                    Logout
                </button>
            </form>
        </div>
    </div>
    <div class="riwayat-page">
        <form method="GET" action="{{ route('modelgrafik') }}" class="filter-box">
            <div class="filter-group">
                <label>Mode</label>
                <select name="mode" onchange="this.form.submit()">
                    <option value="harian" {{ $mode == 'harian' ? 'selected' : '' }}>
                        Harian
                    </option>
                    <option value="bulanan" {{ $mode == 'bulanan' ? 'selected' : '' }}>
                        Bulanan
                    </option>
                    <option value="tahunan" {{ $mode == 'tahunan' ? 'selected' : '' }}>
                        Tahunan
                    </option>
                </select>
            </div>
            <div class="filter-group">
                <label>Bulan</label>
                <select name="bulan">
                    <option value="">-- Bulan --</option>
                    @foreach($bulan as $i => $b)
                        <option value="{{ $i }}"
                            {{ request('bulan') == $i ? 'selected' : '' }}>
                            {{ $b }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Dari</label>
                <input
                    type="date"
                    name="tanggal_mulai"
                    value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="filter-group">
                <label>Sampai</label>
                <input
                    type="date"
                    name="tanggal_selesai"
                    value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">
                    Filter
                </button>
                <a href="{{ route('modelgrafik') }}" class="btn-reset">
                    Reset
                </a>
            </div>
        </form>
        <div class="chart-card">
            <h3 class="chart-title">
                Grafik Keuntungan
                {{ $mode == 'harian' ? 'Harian' : ($mode == 'bulanan' ? 'Bulanan' : 'Tahunan') }}
            </h3>
            <canvas id="chartKeuntungan" class="chart-canvas"></canvas>
        </div>
    </div>
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {
    const canvas = document.getElementById("chartKeuntungan");
    if (!canvas) return;
    const rawLabels = @json($labels ?? []);
    let chartData = @json($data ?? []);
    const mode = "{{ $mode ?? 'harian' }}";
    let labels = [];
    if (mode === 'harian') {
        labels = rawLabels.map(t => {
            let d = new Date(t);
            return d.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short'
            });
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
    if (chartData.length === 0) {
        chartData = [0];
    }
    new Chart(canvas, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Keuntungan',
                data: chartData,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(ctx){
                            return 'Rp ' + ctx.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
