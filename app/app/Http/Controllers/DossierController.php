<?php

namespace App\Http\Controllers;

use App\Events\DossierEvaluationRemindersEvent;
use App\Models\certified_application;
use App\Models\dossier;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;


class DossierController extends Controller
{
    //

    public function index()
    {

        $dossiers = dossier::join('applications', 'applications.dossier_id', 'dossiers.id')
            ->join('medicinal_products', 'medicinal_products.id', 'applications.medical_product_id')
            ->join('medicines', 'medicines.id', 'medicinal_products.medicine_id')
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
        //$paths = Utils::getDirContents($dossier->path);


        $config_dossier_lifetime = Config::get('site_vars.dossier_lifetime_months');

        $certified_applications = certified_application::where('dossier_id', $dossier_id)
            ->join('applications', 'certified_applications.application_id', 'applications.id')
            ->select('certified_applications.*')
            ->first();

        if($certified_applications == null){
            return Redirect()->back()->with('danger', 'No Certified Dossier Applications Found.');
        }

        $certified_date = $certified_applications->certified_date;
        $expire_date = $certified_applications->expire_date;
        // convert to Carbon date format to use its methods
        $certified_date = Carbon::create($certified_date);

        $expire_date = Carbon::create($expire_date);
        //todo expire is updating in-place
        $dossier_delete_due = $expire_date->addYears(5);

        $diff_in_months = $certified_date->diffInMonths(Carbon::now());
        $remaining_months = $config_dossier_lifetime - $diff_in_months;

        $paths = Storage::disk('dossier')->allFiles($dossier->path);

        return view('dossier.show',
            ['paths' => $paths,
                'dossier' => $dossier,
                'certified_applications' => $certified_applications,
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

        //$dossier = dossier::find($dossier_id);
        //$app_id = $dossier->application->id;
        //$ca = certified_application::where('applications_id', $app_id);
        // $ca = certified_application::where('', 1);

        $config_dossier_lifetime = Config::get('site_vars.dossier_lifetime_months');

        $certified_applications = certified_application::where('dossier_id', $dossier_id)
            ->join('applications', 'certified_applications.application_id', 'applications.id')
            ->select('certified_applications.*')
            ->first();

        $certified_date = $certified_applications->certified_date;
        $expire_date = $certified_applications->expire_date;

        // convert to Carbon date format to use its methods
        $certified_date = Carbon::create($certified_date);
        $expire_date = Carbon::create($expire_date);
        //todo:check next line $expire_date is updated in place
        // which is not the desired output
        $dossier_delete_due = $expire_date->addYears(5);
        //dd($dossier_delete_due);

        $diff_in_months = $certified_date->diffInMonths($dossier_delete_due);

        if ($diff_in_months < $config_dossier_lifetime) {
            return Redirect()->back()->with('danger', 'Dossier lifetime is now ' . $diff_in_months . ' Months. Dossier can only be
                    deleted  after ' . $config_dossier_lifetime . ' Months.');
        }

        //todo ask condition when in re-registration process
        //2. is it in re-registration process ?


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

            return Redirect()->back()->with('success', ' Successfully Deleted All Dossier Files. ');

        } catch (\Exception $e) {

            return Redirect()->back()->with('danger', 'Delete Failed. ERROR: ' . $e->getMessage());
        }


    }

}
