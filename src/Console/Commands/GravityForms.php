<?php

namespace ReesMcIvor\GravityForms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use ReesMcIvor\GravityForms\Models\GravityForm;
use ReesMcIvor\GravityForms\Models\GravityFormEntry;

class GravityForms extends Command
{
    protected $signature = 'gravity-forms';

    protected $description = 'Import Gravity Forms';

    public function handle()
    {
        $forms = $this->getClient()->get('forms', ["paging" => ["page_size" => 50]]);
        if(!$forms->successful()) throw new \Exception("Unable to get gravity forms");

        foreach($forms->json() as $form)
        {
            $formFields = $this->getClient()->get('forms/' . $form['id']);
            if(!$formFields->successful()) throw new \Exception("Unable to get gravity forms");

            GravityForm::updateOrCreate(['id' => $form['id']], [
                'id' => $form['id'],
                'name' => $formFields['title'],
                'fields' => $formFields['fields']
            ]);
        }

        $page = 1;
        do {

            $query = [
                "_labels" => 1,
                "paging" => [
                    "current_page" => $page,
                    "page_size" => 500
                ]
            ];
            $formEntries = $this->getClient()->get("entries", $query);

            if(!$formEntries->successful()) throw new \Exception("Unable to get form entries");

            $formEntries = $formEntries->json();

            if(!$formEntries['entries']) break;

            foreach ($formEntries['entries'] as $formEntry) {
                $gravityFormEntry = GravityFormEntry::updateOrCreate(['id' => $formEntry['id']], [
                    'id' => $formEntry['id'],
                    'gravity_form_id' => $formEntry['form_id'],
                    'entry' => $formEntry,
                    'fields' => $this->formatEntry($formEntry),
                    'created_at' => $formEntry['date_created'],
                    'updated_at' => $formEntry['date_updated']
                ]);

                event(new \ReesMcIvor\GravityForms\Events\GravityFormEntryCreated($gravityFormEntry));
                
            }

            $page++;

        } while(true);


    }

    function formatEntry($formEntry)
    {
        $entryFormatted = [];
        $labels = $this->flattenArrayWithKeys($formEntry['_labels']);
        foreach ($labels as $id => $label) {
            $entryFormatted[$label] = isset($formEntry[$id]) ? $formEntry[$id] : '';
        }
        $entryFormatted['Source URL'] = $formEntry['source_url'];
        return $entryFormatted;
    }

    function flattenArrayWithKeys($array, $prefix = '') : array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArrayWithKeys($value, $prefix . $key . '_'));
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
    }

    protected function getClient()
    {
        return Http::withBasicAuth(
            config('gravity_forms.api.key'),
            config('gravity_forms.api.secret')
        )->baseUrl(config('gravity_forms.api.base_uri'));
    }

}
