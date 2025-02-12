@extends('layouts.webromadan_frontend.fe_master')
@section('css_fe')
<style>
    .input-with-logo {
        background-image: url('{{ asset('frontend_romadan_web/images/search_romadan_peraturan.svg') }}');
        background-size: 20px;
        background-position: 10px center;
        background-repeat: no-repeat;
        padding-left: 40px;
    }

    /* Add styles for better text wrapping and formatting */
    .romadan-peraturan-data td {
        white-space: pre-line;
        word-wrap: break-word;
        max-width: 0;
        padding: 15px 20px;
        line-height: 1.6;
    }

    .romadan-peraturan-data th {
        vertical-align: top;
        padding: 15px 20px;
    }

    /* Style for the peraturan number */
    .peraturan-number {
        font-weight: 500;
        color: #2c3e50;
    }

    /* Style for the peraturan title */
    .peraturan-title {
        color: #34495e;
    }

    /* Add subtle border between rows */
    .romadan-peraturan-data tr {
        border-bottom: 1px solid #f0f0f0;
    }

    /* Hover effect for rows */
    .romadan-peraturan-data tr:hover {
        background-color: #f8f9fa;
    }

    /* Make the download button more prominent */
    .btn-peraturan-download {
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<section class="section-welcome p-t-120 p-b-105" style="background-color: white;">
    <div class="container">
        <div class="title-section-ourmenu m-b-22">
            <h5 class="txt-judul-infopublik m-t-2">
                Informasi Publik
            </h5>
            <br>
        </div>
        <div class="row">
            <div class="col-lg-12 text-left">
                <h5 class="romadan-peraturan-detail m-t-2">
                    Detail Peraturan
                </h5>
                <table class="table mt-5 table-borderless romadan-peraturan-data">
                    <tbody>
                        <tr>
                            <th scope="row" width="400px">Nomor</th>
                            <td class="peraturan-number">{{$data->nomor_peraturan}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">Judul</th>
                            <td class="peraturan-title">{{$data->judul_peraturan}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">Bentuk</th>
                            <td>{{$data->data_jenis_peraturan->nama_jenis_peraturan}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">Tanggal Penetapan</th>
                            <td>{{$data->tanggal_penetapan}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">Tanggal Berlaku Efektif</th>
                            <td>{{$data->tanggal_berlaku}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">Status</th>
                            <td>{{$data->data_status_peraturan->nama_peraturan_status}}</td>
                        </tr>
                        <tr>
                            <th scope="row" width="400px">
                                <a href="{{asset('storage/romadan_file_web/'.$data->file)}}"
                                   class="btn btn-peraturan-download romadan-peraturan-download btn-lg active"
                                   role="button"
                                   aria-pressed="true">
                                    <i class="fa-solid fa-download mr-2"></i>Download Peraturan
                                </a>
                            </th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@section('script_fe')
@endsection
@endsection
