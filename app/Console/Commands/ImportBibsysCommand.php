<?php

namespace App\Console\Commands;

class ImportBibsysCommand extends ImportCommand
{
    protected $recordBuffer = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bibsys  {--force : Whether to delete existing data without asking}
                                           {folder  : The folder to import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Bibsys data';

    protected function importFolder($folder)
    {
        $this->info("Importing object records");
        $this->importObjektPoster($folder);

        $this->info("Importing document records");
        $this->importDokPoster($folder);

        $this->info("Refreshing materialized view");
        \DB::unprepared('REFRESH MATERIALIZED VIEW bibsys_search');
    }

    protected function importObjektPoster($folder)
    {
        $marcFiles = "$folder/objektposter.linje/*.mrc";
        $objektId = '';
        $titleStatement = '';
        $pubDate = '';
        $n = 0;
        $marcField = '000';
        $marcRecord = '';
        $marcRecordText = '';
        foreach (glob($marcFiles) as $filename) {
            $this->comment("Importing $filename");
            $fn = fopen($filename, "r");
            while (!feof($fn)) {
                $result = rtrim(fgets($fn));

                if ($result === "^") {
                    if ($objektId != '') {
                        $this->storeRecord($objektId, trim($marcRecord), trim($marcRecordText), $titleStatement, $pubDate);
                    }
                    $marcRecord = '';
                    $marcRecordText = '';
                    $objektId = '';
                    $titleStatement = '';
                    $pubDate = '';
                    $n++;
                    if ($n % 1000 == 0) {
                        $this->comment("Imported $n rows");
                    }
                    continue;
                }

                if (substr($result, 0, 1) === '$') {
                    $bf = substr($result, 1, 1);
                    $marcRecord .= ' '.$result;
                    $marcRecordText .= ' '.mb_substr($result, 2);
                    if ($marcField == '245') {
                        if ($bf == 'b') {
                            $delim = ' : ';
                        } elseif ($bf == 'c') {
                            $delim = ' / ';
                        } else {
                            $delim = ' ';
                        }
                        $titleStatement .= $delim.mb_substr($result, 2);
                    }
                    if ($marcField == '260' && $bf == 'c') {
                        $pubDate = mb_substr($result, 2);
                    }
                    continue;
                }

                if (preg_match('/^\*000 ([0-9Xx]{9}$)/', $result)) {
                    $marcField = '000';
                    $objektId = mb_substr($result, 5);
                    $marcRecord .= "\n$result";
                    $marcRecordText .= ' '.mb_substr($result, 5);
                } elseif (preg_match('/^\*([0-9]{3})/', $result, $matches)) {
                    $marcField = $matches[1];
                    $marcRecord .= "\n$result";
                    $marcRecordText .= ' '.mb_substr($result, 8);
                }
                if (preg_match('/^\*245..\$a/', $result)) {
                    $marcField = '245';
                    $titleStatement = mb_substr($result, 8);
                }
                if (preg_match('/^\*260..\$a/', $result)) {
                    $marcField = '260';
                    $pubDate = '';
                }
            }
            fclose($fn);
        }
        $this->flushRecordBuffer();
    }

    /**
     * @param string $objektId
     * @param string $marcRecord
     * @param string $marcRecordText
     * @param string $titleStatement
     * @param string $pubDate
     */
    public function storeRecord($objektId, $marcRecord, $marcRecordText, $titleStatement, $pubDate)
    {
        $row = [
            'objektid' => $objektId,
            'title_statement' => $titleStatement,
            'pub_date' => empty($pubDate) ? null : $pubDate,
            'marc_record' => $marcRecord,
            'marc_record_text' => $marcRecordText,
        ];
        $this->recordBuffer[] = $row;
        if (count($this->recordBuffer) >= 100) {
            $this->flushRecordBuffer();
        }
    }

    protected function flushRecordBuffer()
    {
        if (count($this->recordBuffer)) {
            \DB::table('bibsys')->insert($this->recordBuffer);
            $this->recordBuffer = [];
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $folder = $this->argument('folder');
        $force = $this->option('force');

        $this->comment('');
        $this->comment(sprintf("Preparing import at host '%s'", \DB::getConfig('host')));

        if (env('APP_ENV') === 'production') {
            $this->error('This is the production environment!!!');
            $force = false;
        }

        if (!$this->ensureEmpty('bibsys', $force)) {
            return;
        }

        if (!$this->ensureEmpty('bibsys_dok', $force)) {
            return;
        }

        // -------------

        $this->comment('Importing bibsys');
        $this->importFolder($folder);

        $this->comment('Import complete');
    }

    protected function importDokPoster($folder)
    {
        $keys = [
            'objektid',
            'dokid',
            'status',
            'strekkode',
            'avdeling',
            'samling',
            'hyllesignatur',
            'deponert',
            'lokal_anmerkning',
            'beholdning',
            'utlaanstype',
            'lisensbetingelser',
            'tilleggsplassering',
            'intern_bemerkning_aapen',
            'intern_bemerkning_lukket',
            'bestillingstype',
            'statusdato',
            'seriedokid',
            'har_hefter',
        ];
        $folder = rtrim($folder, '/');
        $beholdn = "$folder/beholdn_k.csv";

        $row = 1;
        $handle = fopen($beholdn, "r");

        $rows = [];

        $n = 0;

        while (($values = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($values[0] == 'Objektid') {
                // Skip header
                continue;
            }
            $row = [];
            foreach ($values as $k => $value) {
                if (isset($keys[$k])) {
                    if ($keys[$k] == 'intern_bemerkning_lukket') {
                        // skip
                    } else {
                        // Normalize ID fields as lowercase
                        if (in_array($keys[$k], ['objektid', 'dokid', 'seriedokid', 'strekkode'])) {
                            $value = mb_strtolower($value);
                        }

                        $row[$keys[$k]] = ($value === '') ? null : $value;
                    }
                }
            }
            $rows[] = $row;
            if (count($rows) >= 1000) {
                $n += count($rows);
                if (\DB::table('bibsys_dok')->insert($rows)) {
                    $this->comment("Imported $n rows");
                }
                $rows = [];
            }
        }
        fclose($handle);

        $n += count($rows);
        if (\DB::table('bibsys_dok')->insert($rows)) {
            $this->comment("Imported $n rows");
        }
    }
}
