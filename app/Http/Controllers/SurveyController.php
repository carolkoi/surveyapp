<?php

namespace App\Http\Controllers;

use App\DataTables\SurveyDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\Allocation;
use App\Models\Options;
use App\Models\SentSurveys;
use App\Models\SurveyType;
use App\Repositories\SentSurveysRepository;
use App\Repositories\SurveyRepository;
use App\Models\Template;
use App\Models\Response;
use Carbon\Carbon;
use DateTime;
use Flash;
use App\Http\Controllers\AppBaseController;

//use Response;

class SurveyController extends AppBaseController
{
    /** @var  SurveyRepository */
    private $surveyRepository;
    private $sentSurveysRepository;


    public function __construct(SurveyRepository $surveyRepo, SentSurveysRepository $sentSurveyRepo)
    {
        $this->surveyRepository = $surveyRepo;
        $this->sentSurveysRepository = $sentSurveyRepo;
    }

    /**
     * Display a listing of the Survey.
     *
     * @param SurveyDataTable $surveyDataTable
     * @return Response
     */
    public function index(SurveyDataTable $surveyDataTable)
    {
        return $surveyDataTable->render('surveys.index');
    }

    /**
     * Show the form for creating a new Survey.
     *
     * @return Response
     */
    public function create()
    {
        return view('surveys.create');
    }

    /**
     * Store a newly created Survey in storage.
     *
     * @param CreateSurveyRequest $request
     *
     * @return Response
     */
    public function store(CreateSurveyRequest $request)
    {
        $input = $request->except(['_token', 'survey_uuid', 'template_id', 'total',
            'user_id', 'client_id', 'vendor_id']);
//        check if the end date of survey has passed and if the
// check if late response is set is set to receive late responses
        $late_response_setting = Options::where('option_name', 'receive_late_response')->first();
//        get current date
        $template = Template::find($request->input('template_id'));
        $setting = SurveyType::where('id', $template->survey_type_id)->first();

        if (carbon::now()->lte($template->valid_until) OR ($late_response_setting->value == true)) {
            // check if the uuid already exist, user cant respond to the same survey twice
            $response = Response::where('survey_uuid', '=', $request->input('survey_uuid'))
                ->first();
            // if response does not exist proceed to save
            if ($response == null) {
                foreach ($input as $key => $resp) {
                    $map = explode('_', $key);
                    Response::create([
                        'user_id' => $setting->reponse_status == 1 && $request->input('user_id' != null) ? $request->input('user_id') : NULL,
                        'client_id' => ($setting->reponse_status == 1 && $request->input('client_id') != null) ? $request->input('client_id') : NULL,
                        'vendor_id' => ($setting->reponse_status == 1 && $request->input('vendor_id') != null) ? $request->input('vendor_id') : NULL,
                        'template_id' => $map[1],
                        'question_id' => $map[2],
                        'answer_type' => $map[3],
                        'answer' => is_array($resp) ? json_encode($resp) : (is_int($resp) ? strval($resp) : $resp),
                        'survey_uuid' => $request->input('survey_uuid'),
                        'total' => $request->input('total')
                    ]);
                    $this->sentSurveysRepository->saveResponseUuid($map[1], $request->input('survey_uuid'));


                }

//        $survey = $this->surveyRepository->create($input);

                Flash::success('Survey responses saved successfully.');

                return redirect()->back()->with('filled', 'filled');
            }
            //if response exist exit
            return redirect()->back()->with('error', 'error');
        }
        //if the deadline has passed
        return redirect()->back()->with('passed', 'passed');

    }

    /**
     * Display the specified Survey.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, $token)
    {
        $questions = Template::with(['questions.answer', 'surveyType', 'allocations'])->find($id);
        $allocations = Allocation::where('template_id', $id)->get();
        foreach ($allocations as $allocation){
            if (!empty($allocation->user_id)){
                $user_id = $allocation->user_id;
                $client_id = NULL;
                $vendor_id = NULL;
            }
            if (!empty($allocation->client_id)){
                $user_id = NULL;
                $vendor_id = NULL;
                $client_id = $allocation->client_id;
            }
            if (!empty($allocation->vendor_id)){
                $user_id = NULL;
                $client_id = NULL;
                $vendor_id = $allocation->vendor_id;
            }
        }
        return view('surveys.create', compact('token', 'questions', 'user_id', 'client_id', 'vendor_id'));
    }

    public function preview($id)
    {
        $questions = Template::with(['questions.answer'])->find($id);
        if (empty($questions->questions)) {
            Flash::error('Survey has no questions');

            return redirect(route('templates.index'));
        }

        return view('surveys.showpreview', compact('questions'));
    }

    /**
     * Show the form for editing the specified Survey.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $survey = $this->surveyRepository->find($id);

        if (empty($survey)) {
            Flash::error('Survey not found');

            return redirect(route('surveys.index'));
        }

        return view('surveys.edit')->with('survey', $survey);
    }

    /**
     * Update the specified Survey in storage.
     *
     * @param int $id
     * @param UpdateSurveyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSurveyRequest $request)
    {
        $survey = $this->surveyRepository->find($id);

        if (empty($survey)) {
            Flash::error('Survey not found');

            return redirect(route('surveys.index'));
        }

        $survey = $this->surveyRepository->update($request->all(), $id);

        Flash::success('Survey updated successfully.');

        return redirect(route('surveys.index'));
    }

    /**
     * Remove the specified Survey from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $survey = $this->surveyRepository->find($id);

        if (empty($survey)) {
            Flash::error('Survey not found');

            return redirect(route('surveys.index'));
        }

        $this->surveyRepository->delete($id);

        Flash::success('Survey deleted successfully.');

        return redirect(route('surveys.index'));
    }
}
