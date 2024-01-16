
<div class="modal-header">
    <h5 class="modal-title">
        <i class="fa fa-file-alt"></i>&nbsp;{{$attach->nombre}}</h5>
    <button type="button" class="btn-close text-primary" data-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <a href="/storage/reports/{{ $workorder->cod }}/{{$attach->path."?".rand()}}." target="_blank">
            <img src="/storage/reports/{{ $workorder->cod }}/{{$attach->path."?".rand()}}." style="width: 100%; " alt="Sin imagen para mostrar">
        </a>
    </div>
</div>

