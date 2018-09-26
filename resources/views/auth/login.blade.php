@extends('auth.contenido')

@section('login')
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card-group">
            <div class="card p-4">
              <form class="form-horizontal was-validated" method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}
                <div class="card-body">
                  <h2>Acceder</h2>
                  <p class="text-muted"></p>
                  <div class="input-group mb-3{{$errors->has('usuario' ? 'is-invalid' : '')}}">
                      <span class="input-group-addon"><i class="icon-user"></i></span>  
                      <input class="form-control" type="text"  value="{{old('usuario')}}" name="usuario" id="usuario" placeholder="Usuario">
                      {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!}
                  </div>

                  <div class="input-group mb-4{{$errors->has('password' ? 'is-invalid' : '')}}">
                      <span class="input-group-addon"><i class="icon-lock"></i></span>
                      <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                      {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!}
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">Acceder</button>
                    </div>
                    <div class="col-6 text-right">
                      <button class="btn btn-link px-0" type="button">Forgot password?</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <h3>Sistema de ventas</h3>
                  <p class="text-justify">Los productos que le intereza a precios bajos con calidad y garantia.</p>
                  <p class="text-justify">Nuestro personal se encarga de buscar todas las alternativas a costos minimos. Creame, no le cobramos comisión.</p>
                  <button class="btn btn-primary active mt-3" type="button">Registrese Ahora!</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@endsection
