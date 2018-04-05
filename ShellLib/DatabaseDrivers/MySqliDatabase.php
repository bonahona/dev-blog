<?php
class MySqliDatabase implements IDatabaseDriver
{
    public $Database;
    public $Config;

    function __construct($core, $config)
    {
        if(!$config['Database']['UseDatabase']){
            return;
        }

        $db = new mysqli(
            $config['Database']['Server'],
            $config['Database']['Username'],
            $config['Database']['Password'],
            $config['Database']['Database']
        );

        if($db->connect_errno > 0){
            trigger_error('Unable to connect to database, ' . $db->connect_error, E_USER_WARNING);
        }

        $this->Database = $db;
        $this->Config = $config;

        $db->select_db($config['Database']['Database']);
    }

    public function DescribeTable($tableName)
    {
        $sql = 'describe ' . $tableName;
        $resultSet = $this->Database->query($sql);

        if(!$resultSet){
            return false;
        }

        $columns = array();
        $referenceRows = array();
        while($row = mysqli_fetch_assoc($resultSet)){
            $row['PreparedStatementType'] = $this->GetPreparedStatementType($row['Type']);
            $columns[$row['Field']] = $row;

            if($row['Key'] == 'PRI'){
                $primaryKey = $row['Field'];
            }else if($row['Key'] == 'MUL'){
                $referenceRows[] = $row;
            }
        }

        // Handle the references
        $references = array();
        foreach($referenceRows as $referenceRow){
            // Use the standard to determine what row is being references
            if(strpos($referenceRow['Field'], 'Id') !== false){
                $columnName = str_replace('Id', '', $referenceRow['Field']);
                $references[] = $columnName;
            }
        }

        // Find and set some metadata
        $metaData = array(
            'TableName' => $tableName,
            'PrimaryKey' => $primaryKey,
            'ColumnNames' => $this->GetColumnNames($columns, $primaryKey),
            'References' => $references
        );

        $result = array(
            'MetaData' => $metaData,
            'Columns' => $columns
        );

        return $result;
    }

    // Gets a list containing only the headers of the columns and leaves the primary key columns out
    function GetColumnNames($columns, $primaryKey)
    {
        $result = array();

        foreach(array_keys($columns) as $key){
            if($key != $primaryKey ){
                $result[] = $key;
            }
        }

        return $result;
    }

    function GetPreparedStatementType($type){
        if(strpos($type, 'int') !== false){
            return "i";
        }elseif(strpos($type,'char') !== false){
            return "s";
        }elseif(strpos($type, 'datetime') !== false){
            return "s";
        }

        return "";
    }

    public function DescribeRelation($class, $column)
    {
        trigger_error("MySqli DescribeRelation not implemented", E_USER_ERROR);
    }

    public function Close()
    {
        if($this->Database != null) {
            $this->Database->close();
        }
    }

    public function Find($modelCollection, $id)
    {
        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $primaryKey = $modelCollection->ModelCache['MetaData']['PrimaryKey'];
        $columns = array_keys($modelCollection->ModelCache['Columns']);

        $sqlStatement = "SELECT * FROM $tableName WHERE $primaryKey = ?;";
        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }


        $params = array(
            $modelCollection->ModelCache['Columns'][$primaryKey]['PreparedStatementType'],
            &$id
        );

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        $preparedStatement->execute();
        if(!$meta = $preparedStatement->result_metadata()){
            return null;
        }

        // Make sure something was found ur return null
        $preparedStatement->store_result();
        if($preparedStatement->num_rows == 0){
            return null;
        }

        $fields = array();
        foreach($columns as $column){
            $name = $column;
            $$name = null;
            $fields[$name] = &$$name;
        }

        call_user_func_array(array($preparedStatement, 'bind_result'), $fields);

        $preparedStatement->fetch();
        $preparedStatement->close();

        $result = new $modelCollection->ModelName($modelCollection);
        $result->FlagAsSaved();
        foreach($fields as $key => $value){
            $result->$key = $value;
        }

        return $result;
    }

    public function Exists($modelCollection, $id)
    {
        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $primaryKey = $modelCollection->ModelCache['MetaData']['PrimaryKey'];

        $sqlStatement = "SELECT count($primaryKey) as elementExists FROM $tableName WHERE $primaryKey = ?;";
        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }

        $params = array(
            $modelCollection->ModelCache['Columns'][$primaryKey]['PreparedStatementType'],
            &$id
        );

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        $preparedStatement->execute();
        if(!$meta = $preparedStatement->result_metadata()){
            return false;
        }

        // Make sure something was found ur return null
        $preparedStatement->store_result();
        if($preparedStatement->num_rows == 0){
            return false;
        }

        $elementExists = "";
        $fields = array(
            'elementExists' => &$elementExists
        );

        call_user_func_array(array($preparedStatement, 'bind_result'), $fields);

        $preparedStatement->fetch();
        $preparedStatement->close();

        if($elementExists == 0){
            return false;
        }

        return true;
    }

    public function Where($modelCollection, $conditions)
    {
        $result = new Collection();

        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $columns = array_keys($modelCollection->ModelCache['Columns']);

        if(!is_array($conditions)){
            return null;
        }

        $whereClause = "";
        $whereType = array();
        foreach($conditions as $key => $value){
            $whereClause[] = "$key = ?";
            if(is_string($value)){
                $whereType[] = STRING;
            }else if(is_int($value)){
                $whereType[] = INT;
            }
        }

        $whereClause = implode($whereClause," AND ");
        $whereType = implode($whereType,"");
        $sqlStatement = "SELECT * FROM $tableName WHERE $whereClause";

        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }

        $params = array(&$whereType);
        foreach($conditions as &$value){
            $params[] = &$value;
        }

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        $preparedStatement->execute();
        if(!$meta = $preparedStatement->result_metadata()){
            return null;
        }

        $fields = array();
        foreach($columns as $column){
            $name = $column;
            $$name = null;
            $fields[$name] = &$$name;
        }

        call_user_func_array(array($preparedStatement, 'bind_result'), $fields);

        while($preparedStatement->fetch()){
            $item = new $modelCollection->ModelName($modelCollection);
            $item->FlagAsSaved();
            foreach($fields as $key => $value){
                $item->$key = $value;
            }

            $result->Add($item);
        }

        $preparedStatement->close();

        return $result;
    }

    public function Any($modelCollection, $conditions)
    {
        throw new Exception("MySqli::Any() not implemented");
    }

    public function First($modelCollection)
    {
        throw new Exception("MySqli::First() not implemented");
    }

    public function All($modelCollection)
    {
        $result = new Collection();

        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $columns = array_keys($modelCollection->ModelCache['Columns']);

        $sqlStatement = "SELECT * FROM $tableName";
        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }

        $preparedStatement->execute();
        if(!$meta = $preparedStatement->result_metadata()){
            return null;
        }

        $fields = array();
        foreach($columns as $column){
            $name = $column;
            $$name = null;
            $fields[$name] = &$$name;
        }

        call_user_func_array(array($preparedStatement, 'bind_result'), $fields);

        while($preparedStatement->fetch()){
            $item = new $modelCollection->ModelName($modelCollection);
            $item->FlagAsSaved();
            foreach($fields as $key => $value){
                $item->$key = $value;
            }

            $result->Add($item);
        }

        $preparedStatement->close();

        return $result;
    }

    public function Delete($modelCollection, $model)
    {
        if(!$model->IsSaved()){
            return;
        }

        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $primaryKey = $modelCollection->ModelCache['MetaData']['PrimaryKey'];
        $id = $model->$primaryKey;

        $sqlStatement = "DELETE FROM $tableName WHERE $primaryKey = ?;";
        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }


        $params = array(
            $this->ModelCache['Columns'][$primaryKey]['PreparedStatementType'],
            &$id
        );

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        $preparedStatement->execute();
        $preparedStatement->Close();
    }

    public function Insert($modelCollection, &$model)
    {
        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $columns = implode($modelCollection->ModelCache['MetaData']['ColumnNames'], ',');
        $valuePlaceHolders = implode(CreateArray('?', count($modelCollection->ModelCache['MetaData']['ColumnNames'])),',');

        // Create the required SQL
        $sqlStatement = "INSERT INTO $tableName($columns) VALUES($valuePlaceHolders);";

        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }

        $preparedStatementTypes = array();
        $values = array();
        foreach($modelCollection->ModelCache['MetaData']['ColumnNames'] as $key){
            $preparedStatementTypes[] = $modelCollection->ModelCache['Columns'][$key]['PreparedStatementType'];
            $values[] = $model->$key;
        }

        $preparedStatementTypes = implode($preparedStatementTypes);
        $params = array();
        $params[] = $preparedStatementTypes;
        foreach($values as $key => $value){
            $params[] = &$values[$key];
        }

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        if(!$preparedStatement->execute()){
            echo $this->Database->error;
        }

        $insertId = $this->Database->insert_id;

        $primaryKey = $modelCollection->ModelCache['MetaData']['PrimaryKey'];
        $model->$primaryKey = $insertId;

        $preparedStatement->close();
    }

    public function Update($modelCollection, $model)
    {
        if(!$model->IsDirty()){
            return;
        }

        $tableName = $modelCollection->ModelCache['MetaData']['TableName'];
        $primaryKey = $modelCollection->ModelCache['MetaData']['PrimaryKey'];
        $columns = $modelCollection->ModelCache['MetaData']['ColumnNames'];

        $values = array();
        foreach($columns as  $column){
            $values[] = $column . '=?';
        }
        $values = implode($values, ',');

        // Create the required SQL
        $sqlStatement = "UPDATE $tableName SET $values WHERE $primaryKey=?";

        if(!$preparedStatement = $this->Database->prepare($sqlStatement)){
            echo $this->Database->error;
        }

        $preparedStatementTypes = array();
        $values = array();
        foreach($modelCollection->ModelCache['MetaData']['ColumnNames'] as $key){
            $preparedStatementTypes[] = $modelCollection->ModelCache['Columns'][$key]['PreparedStatementType'];
            $values[] = $model->$key;
        }
        $preparedStatementTypes[] = $modelCollection->ModelCache['Columns'][$primaryKey]['PreparedStatementType'];

        $id = $model->$primaryKey;
        $preparedStatementTypes = implode($preparedStatementTypes);

        $params = array();
        $params[] = $preparedStatementTypes;
        foreach($values as $key => $value){
            $params[] = &$values[$key];
        }
        $params[] = &$id;

        call_user_func_array(array($preparedStatement, 'bind_param'), $params);
        $preparedStatement->execute();
        $preparedStatement->close();
    }
}