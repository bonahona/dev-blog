<?php
class Scaffolding
{
    private $Controller;
    private $Form;

    public function __construct($controller, $formsHelper)
    {
        $this->Controller = $controller;
        $this->Form = $formsHelper;
    }

    protected function GetClassDescription($model)
    {
        $modelDescription = $model->GetDescription()->ModelCache;
        $modelName = get_class($model);

        return array(
            'Name' => $modelName,
            'Description' => $modelDescription
        );
    }

    public function Create($model)
    {
        $modelDescription = $this->GetClassDescription($model);
        $references = $modelDescription['Description']['References'];
        ob_start();

        echo '<h1>Create ' . $modelDescription['Name'] . '</h1>';
        echo $this->Form->Start($modelDescription['Name']);

        foreach($modelDescription['Description']['MetaData']['ColumnNames'] as $columnName){

            $required = 'false';
            $currentColumnDescription = $modelDescription['Description']['Columns'][$columnName];

            if($currentColumnDescription['Null'] == 'NO'){
                $required = 'true';
            }

            echo '<div class="row">';
            echo '<label>' . $columnName . '</label>';
            echo $this->Form->Input($columnName, array('attributes' => array('class' => 'form-control', 'required' => $required)));
            echo '</div>';
        }

        echo '<div class="row">';
        echo $this->Form->Submit('Create', array('attributes' => array('class' => 'btn btn-md btn-primary col-lg-2')));
        echo '</div>';
        echo $this->Form->End();

        $result = ob_get_clean();
        return $result;
    }

    public function Read($model)
    {
        $modelDescription = $this->GetClassDescription($model);
        $primaryKeyName = $modelDescription['Description']['MetaData']['PrimaryKey'];
        $references = $modelDescription['Description']['References'];
        ob_start();

        echo '<h1>Read ' . $modelDescription['Name'] . '</h1>';

        echo '<dl>';
        echo '<dt>' . $primaryKeyName . '</dt>';
        echo '<dd>' . $model->$primaryKeyName . '</dd>';
        echo '</dl>';

        foreach($modelDescription['Description']['MetaData']['ColumnNames'] as $columnName){
            $currentColumnDescription = $modelDescription['Description']['Columns'][$columnName];

            echo '<dl>';
            echo '<dt>' . $columnName . '</dt>';
            echo '<dd>' . $columnName . '</dd>';
            echo '</dl>';
        }

        $result = ob_get_clean();
        return $result;
    }

    public function Update($model)
    {
        $modelDescription = $this->GetClassDescription($model);
        $primaryKeyName = $modelDescription['Description']['MetaData']['PrimaryKey'];
        $references = $modelDescription['Description']['References'];
        ob_start();

        echo '<h1>Edit ' . $modelDescription['Name'] . '</h1>';
        echo $this->Form->Start($modelDescription['Name']);
        echo $this->Form->Hidden($primaryKeyName);

        foreach($modelDescription['Description']['MetaData']['ColumnNames'] as $columnName){

            $required = 'false';
            $currentColumnDescription = $modelDescription['Description']['Columns'][$columnName];

            if($currentColumnDescription['Null'] == 'NO'){
                $required = 'true';
            }

            echo '<div class="row">';
            echo '<label>' . $columnName . '</label>';
            echo $this->Form->Input($columnName, array('attributes' => array('class' => 'form-control', 'required' => $required)));
            echo '</div>';
        }

        echo '<div class="row">';
        echo $this->Form->Submit('Save', array('attributes' => array('class' => 'btn btn-md btn-primary col-lg-2')));
        echo '</div>';
        echo $this->Form->End();

        $result = ob_get_clean();
        return $result;
    }
}