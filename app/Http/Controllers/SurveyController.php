<?php

namespace App\Http\Controllers;

use App\DataTables\SurveyDataTable;
use App\Http\Requests;
use App\Http\Requests\CreateSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\Allocation;
use App\Repositories\SurveyRepository;
use App\Models\Template;
use App\Models\Response;
use DateTime;
use Flash;
use App\Http\Controllers\AppBaseController;
//use Response;

class SurveyController extends AppBaseController
{
    /** @var  SurveyRepository */
    private $surveyRepository;

    public function __construct(SurveyRepository $surveyRepo)
    {
        $this->surveyRepository = $surveyRepo;
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

        $input = $request->except(['_token','survey_uuid']);
        //check if the end date of survey has passed and if the setting is set to receive late responses
        //get current date
//        $now = new DateTime();
//        $template = Template::find($request->input('template_id'));
//        dd($template);


        // check if the uuid already exist, user cant respond to the same survey twice
        $response =Response::where('survey_uuid', '=', $request->input('survey_uuid'))
            ->first();
        // if response does not exist proceed to save
        if ($response == null) {
            foreach ($input as $key => $resp) {

                $map = explode('_', $key);

                Response::create([
                    'user_id' => NULL,
                    'client_id' => NULL,
                    'template_id' => $map[1],
                    'question_id' => $map[2],
                    'answer_type' => $map[3],
                    'answer' => is_array($resp) ? json_encode($resp) : (is_int($resp) ? strval($resp) : $resp),
                    'survey_uuid' => $request['survey_uuid']
                ]);
            }

//        $survey = $this->surveyRepository->create($input);

            Flash::success('Survey responses saved successfully.');

            return redirect()->back()->with('filled', 'filled');
        }
        //if response exist exit
        return redirect()->back()->with('error', 'error');


    }

    /**
     * Display the specified Survey.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id, $token)
    {
//        $survey = $this->surveyRepository->find($id);

//        if (empty($survey)) {
//            Flash::error('Survey not found');
//
//            return redirect(route('surveys.index'));
//        }
        $questions = Template::with(['questions.answer'])->find($id);
        return view('surveys.create', compact('token','questions'));
    }

    /**
     * Show the form for editing the specified Survey.
     *
     * @param  int $id
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
     * @param  int              $id
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
     * @param  int $id
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
