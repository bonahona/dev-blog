<?php
require_once('../../ShellLib/Core/Core.php');

class MigrateDatabaseCore extends Core
{
    public function RunExportDatabase($targetFile = null)
    {
        echo "Exporting database to " . $targetFile;

        $tables = array();

        foreach($this->Models->GetAll() as $modelCollection) {
            $tableEntries = array();

            $keys = $modelCollection->Keys();

            foreach($keys as $id) {
                $entry = $modelCollection->Find($id);
                $tableEntries[] = $entry->Object();
            }

            $tables[$modelCollection->ModelCache['MetaData']['TableName']] = $tableEntries;
        }

        $fileContent = json_encode($tables, JSON_PRETTY_PRINT);
        file_put_contents($targetFile, $fileContent);
    }

    public function RunImportDatabase($sourceFile)
    {
        // Make sure the source file exists
        echo "Clearing current database:\n";
        echo "========================================\n";
        foreach($this->Models->GetAll() as $modelCollection){
            echo "Clearing table " . $modelCollection->ModelCache['MetaData']['TableName'] . "\n";
            $modelCollection->Clear();
        }

        echo "Importing source file:\n";
        //echo
    }

    public function RunMigrateDatabase($action)
    {
        echo "Migrating database: $action \n";
        echo "========================================\n";

        $interpretedAction = $this->GetActionFromString($action);
        if($interpretedAction === false){
            echo 'Not a valid action';
            die();
        }
        $this->SetupCapabilities($this->GetActionCapabilitiesRequired($interpretedAction));
        $this->MigrateDatabase($interpretedAction);
    }

    private function GetActionFromString($action)
    {
        $actions = array(
            'up' => MIGRATION_UP,
            'down' => MIGRATION_DOWN,
            'seed' => MIGRATION_SEED
        );

        if(array_key_exists($action, $actions)){
            return $actions[$action];
        }else{
            return false;
        }
    }

    private function GetActionCapabilitiesRequired($action)
    {
        $capabilities = array(
            MIGRATION_UP => array(CAPABILITIES_DATABASE),
            MIGRATION_DOWN => array(CAPABILITIES_DATABASE),
            MIGRATION_SEED => array(CAPABILITIES_DATABASE, CAPABILITIES_MODEL_CACHING, CAPABILITIES_MODELS)
        );

        return $capabilities[$action];
    }
}