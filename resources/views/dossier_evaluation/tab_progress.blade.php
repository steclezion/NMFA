{{--------------------START progress tab ------------------}}
<div class="tab-pane fade " id="custom-tabs-three-progress" role="tabpanel"
     aria-labelledby="custom-tabs-three-progress-tab">

    <div class="col-md-8 offset-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Evaluation Checklist</h3>
            </div>


            <div class="row">
                @include('dossier_evaluation.progress_status')
            </div>

        </div>
    </div>
</div>
