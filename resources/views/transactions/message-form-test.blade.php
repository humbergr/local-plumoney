<form class="" target="_blank" action="{{URL::to('/up-tests')}}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <label>Archivo
        <br>
        <input type="file" name="archivoPrueba">
    </label>
    <br>
    <input type="submit" value="Try">
</form>