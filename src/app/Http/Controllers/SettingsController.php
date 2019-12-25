<?php

namespace Vipertecpro\Settings\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Vipertecpro\Settings\App\Http\Requests\SettingRequest;
use Vipertecpro\Settings\App\Setting;

class SettingsController extends Controller
{
    /**
     * @var array
     * available setting types
     */
    private $types = ['TEXT' => 'Text', 'TEXTAREA' => 'Text Area',
        'BOOLEAN' => 'Boolean', 'NUMBER' => 'Number',
        'DATE' => 'Date', 'SELECT' => 'Select Options', 'FILE' => 'File'];

    /**
     * SettingsController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $hidden = [0];
        if (Config::get('settings.show_hidden_records')) {
            $hidden[] = 1;
        }
        $settings = Setting::whereIn('hidden', $hidden);
        $search_query = $request->search;
        $search = [
            'code' => '',
            'type' => '',
            'label' => '',
            'value' => '',
        ];
        if (!empty($search_query)) {
            foreach ($search_query as $key => $value) {
                if (!empty($value)) {
                    $search[$key] = $value;
                    $settings->where($key, 'like', '%' . strip_tags(trim($value)) . '%');
                }
            }
        }
        $types = $this->types;
        $settings = $settings->paginate(Config::get('settings.per_page', 10));
        return view('settings::index')->with(compact('settings', 'search', 'types'));
    }

    /**
     * @param string $type
     * @return Factory|View
     */
    public function create($type = '')
    {
        if (!array_key_exists($type, $this->types)) {
            return redirect()->back()->with('error', 'Invalid type provided');
        }
        return view('settings::create')->with(compact('type'));
    }

    public function store(SettingRequest $request)
    {
        $data = $request->all();
        $setting = Setting::create($data);
        return redirect(url(Config::get('settings.route_prefix'). '/edit/'.$setting->id ))->with('success', 'Record has been created successfully.')
            ->with('new', true);
    }

    public function edit($settings_id = 0)
    {
        $setting = Setting::find($settings_id);
        if($setting === null){
            return redirect(url(Config::get('settings.route_prefix')))->with('error', 'Invalid Setting ID');
        }
        if (Session::has('new')) {
            $new = true;
        } else {
            $new = false;
        }
        if (!$new && $setting->hidden && !Config::get('settings.show_hidden_records')) {
            return redirect(url(Config::get('settings.route_prefix')))->with('error', 'Permission denied!');
        }
        return view('settings::edit')->with(compact('setting'));
    }

    public function update(SettingRequest $request, $settings_id = 0)
    {
        $setting = Setting::find($settings_id);
        if($setting === null){
            return redirect(url(Config::get('settings.route_prefix')))->with('error', 'Invalid Setting ID');
        }
        $setting->code = $request->code;
        $setting->label = $request->label;
        $setting->hidden = @$request->hidden ? '0' : '1';

        switch ($setting->type) {
            case 'TEXT':
            case 'TEXTAREA':
            case 'DATE':
            case 'BOOLEAN':
            case 'NUMBER':
            case 'SELECT':
                $setting->value = trim($request->value);
                break;
            case 'FILE':
                if ($request->hasFile('value')) {
                    @unlink($setting->value);
                    $destinationPath = public_path() . '/' . Config::get('settings.upload_path');
                    if (!File::exists($destinationPath)) {
                        File::makeDirectory($destinationPath, 0775, true);
                    }
                    $value = $setting->code . '.' . $request->file('value')->getClientOriginalExtension();
                    $request->file('value')->move($destinationPath, $value);
                    $setting->value = $value;
                }
                break;
            default:
        }
        $setting->save();
        return redirect(url(Config::get('settings.route_prefix')))->with('success', 'Record has been saved successfully.');
    }

    public function destroy(Request $request, $settings_id = 0)
    {
        $setting = Setting::find($settings_id);
        if($setting === null){
            return redirect(url(Config::get('settings.route_prefix')))->with('error', 'Invalid Setting ID');
        }
        if ($request->ajax()) {
            $tr = 'tr_' . $setting->id;
            if ($setting->type === 'FILE') {
                @unlink($setting->value);
            }
            $setting->delete();
            return response()->json(['success' => 'Record has been deleted successfully', 'tr' => $tr]);
        } else {
            return 'You can\'t proceed in delete operation';
        }
    }

    public function fileDownload(Setting $setting)
    {
        if (empty($setting->value)) {
            abort(404);
        }
        return response()->download($setting->value);
    }
}