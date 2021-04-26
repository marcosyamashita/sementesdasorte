@extends('layout')
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
<script>
var SPMaskBehavior = function (val) {
  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
spOptions = {
  onKeyPress: function(val, e, field, options) {
      field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
};

$('#cpf').mask('000.000.000-00');
$('#telefone').mask(SPMaskBehavior, spOptions);

$('.btn-consuta').click(function(e){
    var ano = $(this).data('ano');
    $('#ano_pesquisa').val(ano);
    e.preventDefault();
    $('#resultado').html("<h1>Carregando...</h1>");
    $.get('/check-cupom/' + $('#cpf').val() + '/' + ano) 
    .then(function(resultado){
        $('#resultado').html(resultado);
    })
    .fail(function(){ $('#resultado').html("<h1 style='color: red;'>Erro. Tente novamente.</h1>"); });
});
$('#btn-imprimir').click(function(e){
    e.preventDefault();
    window.location = "{{url('/check-cupom/print')}}/"+ $('#cpf').val() + '/' + $('#ano_pesquisa').val();
});
$(function(){
    $('#formulario-contato').submit(function(e){
        e.preventDefault();

        var self = $(this);

        $.post(self.attr('action'), self.serialize())
        .then(function(){
            self.siblings('.w-form-done').css('display', 'block');
            self.siblings('.w-form-fail').css('display', 'none');
        })
        .fail(function(){
            self.siblings('.w-form-fail').css('display', 'block');
            self.siblings('.w-form-done').css('display', 'none');
        });

        return false;
    });
});
</script>
@endsection