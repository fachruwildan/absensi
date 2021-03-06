@extends('layouts.mains')
@section('title','Update Kategori Point')
@section('container')

<div class="container">
<div class="row">
<div class="col-10">
    <h1 class="mt-3">Update Kategori Point</h1>
    <form method="post" action="/kategori-point/{{ $kategoriPoint->id }}">
    @method('patch')
    @csrf    

  <div class="form-group">
    <label for="nama" class="mt-3">Nama</label>
    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" 
    placeholder="Masukkan nama" name="nama" value="{{ $kategoriPoint->nama }}">
    @error('nama')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="form-group">
    <label for="jenis_point" class="mt-3">Jenis Point</label>
    <select name="jenis_point" class="form-control @error('jenis_point') is-invalid @enderror" id="jenis_point"
    name="jenis_point" value="{{ $kategoriPoint->jenis_point }}">

        <option value="positif">Positif</option>

        <option value="negatif">Negatif</option>

    </select>
    @error('jenis_point')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
  
  <button type="submit" class="bnt btn-primary">Update Data!</button>
</form>


</div>
</div>
</div>
@endsection

    