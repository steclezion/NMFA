<?php

namespace App\Http\Controllers;

use App\Events\DossierEvaluationRemindersEvent;
use App\Models\certification;
use App\Models\certified_application;
use App\Models\dossier;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DossierController extends Controller
{
    //

    public function index()
    {
        //list dossiers where dossier evaluation is completed (now transferred to decision)

        $dossiers = dossier::join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('dossier_assignments', 'dossier_assignments.application_id', 'applications.id')
            ->join('decisions', 'decisions.dossier_assignment_id', 'dossier_assignments.id')
            ->join('certifications','certifications.decision_id', 'decisions.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
            ->where('dossier_assignments.supervisor_id', auth()->user()->id)
            ->where('dossiers.assignment_status', 4) // 4 = evaluation completed
            //->where('certifications.status', 'reregistration_expired')
            ->select('applications.id',
                'medicinal_products.product_trade_name',
                'medicines.product_name',
                'dossiers.*')
            ->get();
        return view('dossier.index', compact('dossiers'));

    }

    public function show($dossier_id)
    {

        $dossier = dossier::find($dossier_id);

        $config_dossier_lifetime = Config::get('site_vars.dossier_lifetime_months');

        $certification = DB::table('certifications')
            ->join('decisions', 'certifications.decision_id', 'decisions.id')
            ->join('dossier_assignments', 'dossier_assignments.id', 'decisions.dossier_assignment_id')
            ->join('dossiers', 'dossiers.id', 'dossier_assignments.dossier_id')
            ->where('dossier_id', $dossier_id)
            ->select('dossiers.id as dossier_id', 'certifications.*')
        ->first();

        if ($certification == null) {
            return Redirect()->back()->with('danger', 'No Certified Applications Found for Dossier: '. $dossier->dossier_ref_num);
        }

        $certified_date = $certification->certified_date;
        $expiry_date = $certification->expiry_date;
        // convert to Carbon date format to use its methods
        $certified_date = Carbon::create($certified_date);

        $expiry_date = Carbon::create($expiry_date);
        // Registration expires in 5 years. But to delete dossier, wait another 5 years (total 10 years from registration)
        $dossier_delete_due = $expiry_date->addYears(5);
        //Note:  CAUTION - addYears updates $expiry_date in-place !! but since we are not using $expiry_date, it is ok.

        $diff_in_months = $certified_date->diffInMonths(Carbon::now());


        $remaining_months = $config_dossier_lifetime - $diff_in_months;

        $paths = Storage::disk('dossier')->allFiles($dossier->path);


        return view('dossier.show',
            ['paths' => $paths,
                'dossier' => $dossier,
                'certified_applications' => $certification,
                'dossier_delete_due' => $dossier_delete_due,
                'remaining_months' => $remaining_months
            ]);
    }


    /**
     * Permanently delete dossier files after delete-conditions are satisfied
     * Delete-conditions: 1. Dossier registration deadline reached 10 years, no re-registration requested
     *  2. If dossier is under re-registration, do not delete
     * @param int $dossier_id
     * @return RedirectResponse
     */
    public function delete_all($dossier_id)
    {

        //dd($dossier_id);

        try {
            $dossier = dossier::find($dossier_id);
            //$paths = Utils::getDirContents($dossier->path);
            $paths = Storage::disk('dossier')->allFiles($dossier->path);

            //dd($paths);

            if ($paths == []) {
                return Redirect()->back()->with('info', ' Dossier files NOT found. ');
            }

            // delete dir and all of its contents
            Storage::disk('dossier')->deleteDirectory($dossier->path);

            //todo softdelete dossier from db dossier table?
            // other cleaning needed??
            $dossier->delete();

            $assertion = $this->assertSoftDeleted($dossier);

            //dd($assertion);


            return Redirect()->back()->with('success', ' Successfully Deleted All Dossier Files. ');

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Delete Failed. ERROR: ' . $e->getMessage());
        }


    }

}
