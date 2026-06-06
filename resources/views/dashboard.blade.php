@extends('layouts.app')
@section('content')
<style>
.dashboard{
    width:100%;
    display:flex;
    flex-direction:column;
    gap:24px;
}
.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:24px;
}
.card-dashboard{
    background:#fff;
    border-radius:24px;
    padding:24px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
    transition:.3s;
}
.card-dashboard:hover{
    transform:translateY(-3px);
}
.card-dashboard h4{
    margin:0;
    font-size:15px;
    color:#666;
    font-weight:600;
}
.card-dashboard span{
    display:block;
    margin-top:20px;
    font-size:42px;
    font-weight:700;
    color:#111827;
}
.charts-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:24px;
}
.chart-card{
    background:#fff;
    border-radius:24px;
    padding:24px;
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}
.chart-title{
    margin:0 0 20px;
    font-size:22px;
    font-weight:700;
    color:#111827;
}
.chart-wrapper{
    position:relative;
    width:100%;
    height:400px;
}
@media(max-width:992px){
    .stats{
        grid-template-columns:1fr;
    }
    .charts-row{
        grid-template-columns:1fr;
    }
    .card-dashboard span{
        font-size:32px;
    }
    .chart-wrapper{
        height:320px;
    }
}
</style>
<div class="dashboard">
    <div class="stats">
        <div class="card-dashboard">
            <h4>Total Produk</h4>
            <span>{{ $totalBarang }}</span>
        </div>
        <div class="card-dashboard">
            <h4>Barang Terjual</h4>
            <span>{{ $barangTerjual }}</span>
        </div>
        <div class="card-dashboard">
            <h4>Keuntungan Bulan Ini</h4>
            <span>
                Rp {{ number_format($keuntunganBulanan,0,',','.') }}
            </span>
        </div>
    </div>
    <div class="charts-row">
        <div class="chart-card">
            <h3 class="chart-title">
                Grafik Penjualan Bulanan {{ now()->year }}
            </h3>
            <div class="chart-wrapper">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3 class="chart-title">
                Grafik Penjualan Harian (30 Hari Terakhir)
            </h3>
            <div class="chart-wrapper">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Chart(document.getElementById('salesChart'), {
        type:'line',
        data:{
            labels:@json($labels),
            datasets:[{
                label:'Penjualan Bulanan',
                data:@json($data),
                borderColor:'#16a34a',
                backgroundColor:'rgba(22,163,74,.15)',
                borderWidth:3,
                fill:true,
                tension:0.4
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{
                tooltip:{
                    callbacks:{
                        label:function(ctx){
                            return 'Rp ' +
                            Number(ctx.raw)
                            .toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales:{
                y:{
                    beginAtZero:true,
                    ticks:{
                        callback:function(value){
                            return 'Rp ' +
                            Number(value)
                            .toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
    new Chart(document.getElementById('dailyChart'), {
        type:'bar',
        data:{
            labels:@json($labelsHarian),
            datasets:[{
                label:'Penjualan Harian',
                data:@json($dataHarian),
                backgroundColor:'rgba(59,130,246,.55)',
                borderColor:'#2563eb',
                borderWidth:2
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{
                tooltip:{
                    callbacks:{
                        label:function(ctx){
                            return 'Rp ' +
                            Number(ctx.raw)
                            .toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales:{
                y:{
                    beginAtZero:true,
                    ticks:{
                        callback:function(value){
                            return 'Rp ' +
                            Number(value)
                            .toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
