<?php

namespace ReesMcIvor\GravityForms\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use ReesMcIvor\GravityForms\Models\GravityForm;
use ReesMcIvor\GravityForms\Models\GravityFormEntry;
use Tightenco\Collect\Support\Collection;

class GravityForms extends Command
{
    protected $signature = 'gravity-forms {--from= : The from date of the entries to sync}';

    protected $description = 'Import Gravity Forms';

    protected function syncGravityForms()
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
    }

    public function handle()
    {

        $dateFrom = $this->option('from') ? \Carbon\Carbon::parse($this->option('from')) : now()->subDays(7);
        $gravityFormEntries = new \Illuminate\Support\Collection();

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

                $gravityForm = GravityForm::find( $formEntry['form_id'] );
                if(!$gravityForm) {
                    $this->syncGravityForms();
                }

                $dateCreatedCarbon = \Carbon\Carbon::parse($formEntry['date_created']);
                $gravityFormEntry = GravityFormEntry::firstOrNew(['id' => $formEntry['id']]);
                $gravityFormEntry->fill([
                    'id' => $formEntry['id'],
                    'gravity_form_id' => $formEntry['form_id'],
                    'entry' => $formEntry,
                    'fields' => $this->mapping($this->formatEntry($formEntry)),
                    'created_at' => $formEntry['date_created'],
                    'updated_at' => $formEntry['date_updated']
                ]);

                if($dateFrom->lessThanOrEqualTo($dateCreatedCarbon)) {
                    $gravityFormEntries->add($gravityFormEntry);
                    event(new \ReesMcIvor\GravityForms\Events\GravityFormEntryCreateEvent($gravityFormEntry));
                }

                $gravityFormEntry->save();
            }

            $page++;

        } while(true);

        if($gravityFormEntries->count()) {
            event(new \ReesMcIvor\GravityForms\Events\GravityFormsEntriesEvent($gravityFormEntries));
        }

    }

    function mapping( $formEntry ) : array
    {
        $mappings = [
            "Street Address" => "Address Line 1",
            "State / Province" => "Region",
            "ZIP / Postal Code" => "Postcode",
        ];
        foreach($mappings as $old => $new) {
            if(isset($formEntry[$old])) {
                $formEntry[$new] = $formEntry[$old];
                unset($formEntry[$old]);
            }
        }
        return $formEntry;
    }

    function formatEntry($formEntry)
    {
        $entryFormatted = [];
        $labels = $this->flattenArrayWithKeys($formEntry['_labels']);
        $labels = $this->flattenArrayWithKeys($formEntry['_labels']);
        foreach ($labels as $id => $label) {
            $entryFormatted[$label] = isset($formEntry[$id]) ? $formEntry[$id] : '';
        }
        $entryFormatted['Source URL'] = $formEntry['source_url'];
        return $entryFormatted;
    }

    function flattenArrayWithKeys($array) : array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result += $this->flattenArrayWithKeys($value);
            } else {
                $result[$key] = $value;
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
