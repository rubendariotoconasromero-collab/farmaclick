<?php 

function permiso($id_formulario){
    $id_usuario = \Auth::user()->id;     
    $data = DB::select('call permisoUsuario(?, ?)',array($id_usuario, $id_formulario));
    return $data[0]->form;
}