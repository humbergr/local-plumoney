
@foreach (lotes(16) as $item)
    

<div class="col-md-3">
    <div class="form-group{{ $errors->has('lote[]') ? ' has-danger' : '' }}">
    <label class="form-control-label text-primary" for="input-lote[]">{{$item->lote}}</label>
      <input type="text" name="lote[]" id="lote[]"
        class="form-control  form-control-alternative{{ $errors->has('lote[]') ? ' is-invalid' : '' }}"
        placeholder="" value="" >
        <small id="helpId" class="form-text text-muted">Disponible {{$item->monto}} </small>

      @if ($errors->has('lote[]'))
      <span class="invalid-feedback" role="alert">
        <strong>{{ $errors->first('lote[]') }}</strong>
      </span>
      @endif
    </div>
  </div>

  @endforeach

  