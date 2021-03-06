@extends('adminlte::page')
@section('title', 'Labkom FMIPA UNS | Peminjaman Alat')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Daftar Peminjam Alat</h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Peminjaman Alat</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div class="peminjamalat-success" data-flashdata="{{ session('success') }}"></div>
    <div class="peminjamalat-warning" data-flashdata="{{ session('warning') }}"></div>
    <div class="peminjamalat-danger" data-flashdata="{{ session('danger') }}"></div>
@endsection

@section('content')
    <!-- Default box -->
    <div class="card shadow">
        <div class="card-header">
            <h3 class="card-title">Daftar Peminjam Alat</h3>
            <div class="card-tools">
                <form class="form-inline d-inline" method="post" action="{{ route('PeminjamanAlat.daily_report') }}">
                    @csrf
                    @method('post')
                    <input type="hidden" name="kategori" value="peminjaman_alat">
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old("tanggal") }}" autocomplete="off">
                    @error('tanggal')
                        <div class="invalid-feedback d-inline">
                            {{ $message }}
                        </div>
                    @enderror
                    <button type="submit" class="btn btn-outline-dark btn-sm ml-2 mr-2">
                        <i class="fas fa-file-pdf"></i>
                        Cetak per hari
                    </button>
                </form>
                <form action="{{ route('PeminjamanAlat.monthly_report') }}" method="post" class="form-inline d-inline">
                    @csrf
                    @method('post')
                    <input type="hidden" name="kategori" value="peminjaman_alat">
                    <select class="form-control custom-select @error('bulan') is-invalid @enderror" name="bulan">
                        <option disabled selected>-</option>
                        <option value="01">Januari</option>
                        <option value="02">Februari</option>
                        <option value="03">Maret</option>
                        <option value="04">April</option>
                        <option value="05">Mei</option>
                        <option value="06">Juni</option>
                        <option value="07">Juli</option>
                        <option value="08">Agustus</option>
                        <option value="09">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    @error('bulan')
                        <div class="invalid-feedback d-inline">
                            {{ $message }}
                        </div>
                    @enderror
                    <button type="submit" class="btn btn-outline-dark btn-sm ml-2 mr-2">
                        <i class="fas fa-file-pdf"></i>
                        Cetak per bulan
                    </button>
                </form>
                <a href="{{ route('PeminjamanAlat.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-user-plus"></i>
                    Insert
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-bordered">
                <thead class="thead-light">
                <tr>
                    <th class="text-center">
                        No
                    </th>
                    <th class="text-center">
                        Tanggal
                    </th>
                    <th class="text-center">
                        Nama
                    </th>
                    <th class="text-center">
                        Prodi
                    </th>
                    <th class="text-center">
                        Alat
                    </th>
                    <th class="text-center">
                        Status
                    </th>
                    <th >
                        #
                    </th>
                </tr>
                </thead>
                <tbody>

                @foreach($PeminjamanAlat as $elemen)
                    <tr>
                        <td class="text-center">
                            {{ ($PeminjamanAlat->currentPage() - 1) * $PeminjamanAlat->perPage() + $loop->index + 1 }}
                        </td>
                        <td class="text-center">
                            <a class="d-block">
                                {{ $elemen->tanggal_pinjam }} - {{ $elemen->tanggal_kembali }}
                            </a>
                            <small>
                                {{ $elemen->jam_pinjam }} - {{ $elemen->jam_kembali }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a class="d-block">
                                {{ $elemen->mahasiswa->nama_mahasiswa }}
                            </a>
                            <small>
                                {{ $elemen->mahasiswa->kelas }} - {{ $elemen->mahasiswa->nim }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a class="d-block">
                                {{ $elemen->mahasiswa->prodi->nama_prodi }}
                            </a>
                            <small>
                                {{ $elemen->mahasiswa->tahun_angkatan }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a class="d-block">
                                {{ $elemen->alat->nama_alat }}
                            </a>
                            <small>
                                Rp.{{ $elemen->alat->harga_alat }}
                            </small>
                        </td>
                        <td class="text-center">
                            <a>
                                @if($elemen->status === '0')
                                    <i class="fas fa-circle-notch fa-2x"></i>
                                @else
                                    <i class="fas fa-check-circle fa-2x"></i>
                                @endif
                            </a>
                        </td>
                        <td class="project-actions text-right">
                            <button class="btn btn-secondary btn-sm detail-peminjamanalat-button" type="button" data-toggle="modal" data-target="#peminjamanalatModal" data-showurl="{{ route('PeminjamanAlat.show', $elemen->id) }}">
                                <i class="fas fa-folder"></i>
                                Detail
                            </button>
                            <a class="btn btn-info btn-sm" href="{{ route('PeminjamanAlat.edit', $elemen->id) }}">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <form action="{{ route('PeminjamanAlat.destroy', $elemen->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm delete-peminjamanalat-button" type="submit">
                                    <i class="fas fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            <section class="d-flex align-items-center justify-content-center mt-3">
                {{ $PeminjamanAlat->links() }}
            </section>
        </div>
        <!-- /.card-body -->
        <div id="detail-peminjamalat"></div>
    </div>
    <!-- /.card -->
@endsection
