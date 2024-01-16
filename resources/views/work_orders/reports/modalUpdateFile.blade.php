<div class="modal modalPrimary fade modal-slide-in-right" aria-hidden="true" role="dialog" tabindex="-1" id="modalArchivos" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-file-alt"></i>&nbsp;Adjuntar archivo</h5>
                <button type="button" class="btn-close text-primary" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="container-fluid" style="padding: 10px" id="imagen--label">
                        {!! Form::open( array('route' =>'reports.saveFile','method'=>'POST','autocomplete'=>'off','files'=>'true','accept-charset'=>'UTF-8','enctype'=>'multipart/form-data','id'=>'formSubmitFile', 'onsubmit'=>'btnSubmitFile.disabled = true; return true;' )) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="text-center" style='border: solid 1px rgb(219, 214, 214);'>
                                    <label class="cursor-pointer">
                                        <input class="tipoArchivo" name="tipo_archivo" checked id="radioImg" type="radio" value="i">
                                        Subir Imagen
                                    </label> &ensp;&ensp;

                                    <label class="cursor-pointer">
                                        <input class="tipoArchivo" name="tipo_archivo" id="radioFile" type="radio" value="a">
                                        Subir Archivo
                                    </label>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 archivodiv" id="imagen--label" style="margin-top:10px; display:none;">
                            <b>* Adjunte un archivo para el informe </b><br>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                </svg>Tipos de archivos soportados:</b>&nbsp;.pdf, .doc, .docx, .xls, .xlsx, .zip, .rar<br>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                </svg>Tamaño máximo admitido: </b> 5 MB (5192 KB) <br>
                            <input type="file" name="archivo" id="archivoSTReporte" data-max-size="5192" data-browse-on-zone-click="true" accept=".pdf, .doc, .docx, .xls, .xlsx, .zip, .rar" />
                            <span class="text-red" id="archivo-error"></span>
                        </div>

                        {{-- IMAGENES --}}
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 imagendiv" id="imagen--label" style="margin-top:10px">
                            <b>* Adjunte una imagen para el informe </b><br>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                </svg>Tipos de imágenes soportadas:</b>&nbsp;.gif, .jpg, .jpeg, .png<br>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 9h8v-3.586a1 1 0 0 1 1.707 -.707l6.586 6.586a1 1 0 0 1 0 1.414l-6.586 6.586a1 1 0 0 1 -1.707 -.707v-3.586h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1z" />
                                </svg>Tamaño máximo admitido: </b> 15 MB (15192 KB) <br>
                            <b>&nbsp;&nbsp;&nbsp;&nbsp;
                                <svg class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="15" y1="8" x2="15.01" y2="8" /><rect x="4" y="4" width="16" height="16" rx="3" /><path d="M4 15l4 -4a3 5 0 0 1 3 0l5 5" /><path d="M14 14l1 -1a3 5 0 0 1 3 0l2 2" />
                                </svg></b>
                                <b>Las imágenes subidas serán redimensionadas a un tamaño máximo de 1024x1024 px.</b>
                            <input type="file" name="imagen" id="imagenesSTReporte" data-max-size="5192" data-browse-on-zone-click="true" accept=".gif, .jpg, .jpeg, .png" />
                            <span class="text-red" id="imagen-error"></span>
                        </div>

                        <input type="text" name="idModulo" value="{{$workorder->id}}" class="hidden">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label id="titulo--label">* Título del archivo</label>
                                <input type="text" name="titulo" class="form-control" placeholder="Nombre que se mostrará en la Tabla">
                                <span class="text-red" id="titulo-error"></span>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Renombrar archivo</label> <i>(Opcional)</i>
                                <span class="form-help" data-html="true" data-toggle="popover" data-trigger="hover" data-placement="top"
                                    data-content="<p style='font-size:10pxtext-align:justify'>
                                        Los caracteres especiales se reemplazarán.</p>"
                                    data-title="Información"
                                >?</span>
                                <input type="text" name="renombrar" class="form-control" >
                            </div>
                        </div>
                        <input type="text" name="repid" hidden value="{{code($workorder->id)}}" >

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button type="button" class="btn btn-ghost-secondary pull-left" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary pull-right" name="btnSubmitFile">Guardar archivo</button>
                        </div>

                        {{Form::close()}}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
