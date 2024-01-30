@extends('layout')

@section('content')
<div class="modal fade" id="ugyfelCreateModal" tabindex="-1" aria-labelledby="ugyfelCreateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ugyfelCreateModalLabel">Új Ügyfél</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @include('ugyfel.create')
      </div>
    </div>
  </div>
</div>

<a href="#" data-bs-toggle="modal" data-bs-target="#ugyfelCreateModal">
    <div class="hozzaad">+</div>
</a>
@endsection
